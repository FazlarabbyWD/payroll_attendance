<?php
namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\AttendanceProcess;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceProcessingService
{
    const ATTENDANCE_LOGS_TYPE = 'attendance_logs';
    public function processAttendanceLogs()
    {
        Log::channel('attProsLog')->info("Starting attendance processing...");

        $lastProcessed = AttendanceProcess::where('type', self::ATTENDANCE_LOGS_TYPE)->first();
        $lastId        = $lastProcessed?->last_attendance_log_id ?? 0;


        $attendanceLogs = AttendanceLog::selectRaw(
            'employee_id, DATE(timestamp) as log_date, MIN(timestamp) as first_log, MAX(timestamp) as last_log, MAX(id) as last_id'
        )
            ->where('id', '>', $lastId)
            ->groupBy('employee_id', DB::raw('DATE(timestamp)'))
            ->orderBy('employee_id')
            ->orderBy('log_date')
            ->get();

        if ($attendanceLogs->isEmpty()) {
            Log::channel('attProsLog')->info("No new attendance logs found to process.");
            return;
        }

        DB::beginTransaction();

        try {
            foreach ($attendanceLogs as $log) {
                $employeeId = $log->employee_id;
                $date       = Carbon::parse($log->log_date);
                $firstLog   = Carbon::parse($log->first_log);
                $lastLog    = Carbon::parse($log->last_log);

                // Pass first and last log to your existing attendance processor
                $this->processEmployeeAttendance($employeeId, $date, $firstLog, $lastLog);
            }

            // Update last processed info using the largest ID
            $latestLogId = $attendanceLogs->max('last_id');
            $latestLog   = AttendanceLog::find($latestLogId);

            AttendanceProcess::updateOrCreate(
                ['type' => self::ATTENDANCE_LOGS_TYPE],
                [
                    'last_attendance_log_id' => $latestLogId,
                    'last_processed_at'      => $latestLog?->timestamp,
                ]
            );

            DB::commit();
            Log::channel('attProsLog')->info("Attendance processing completed successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('attProsLog')->error("Error processing attendance: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            throw $e;
        }
    }

    protected function processEmployeeAttendance(string $employeeId, Carbon $date, Carbon $firstLog, Carbon $lastLog)
    {
        try {

            $employee = Employee::firstWhere('employee_id', $employeeId);

            if (! $employee) {
                Log::channel('attProsLog')->warning("Employee with ID {$employeeId} not found. Skipping.");
                return;
            }

            $currentCheckIn  = $firstLog;
            $currentCheckOut = $lastLog;

            // Get existing attendance or create new
            $attendance = Attendance::firstOrNew([
                'employee_id' => $employee->employee_id,
                'date'        => $date->toDateString(),
            ]);

            // Correctly Update check-in/check-out
            if (! $attendance->exists) {
                // If it's a new record, assign the first and last logs
                $attendance->check_in  = $currentCheckIn->toTimeString();
                $attendance->check_out = $currentCheckOut->toTimeString();

            } else {
                //If attendance exists compare and set values
                $attendance->check_in  = (Carbon::parse($attendance->check_in))->lessThan($currentCheckIn) ? $attendance->check_in : $currentCheckIn->toTimeString();
                $attendance->check_out = (Carbon::parse($attendance->check_out))->greaterThan($currentCheckOut) ? $attendance->check_out : $currentCheckOut->toTimeString();
            }

            // Calculate total minutes
            $checkInTime  = Carbon::parse($attendance->check_in);
            $checkOutTime = Carbon::parse($attendance->check_out);
            $totalMinutes = $checkInTime->diffInMinutes($checkOutTime);

            // Hardcoded schedule (replace with shift logic if needed)
            $scheduledStartTime   = $date->copy()->setTime(10, 0, 0);
            $scheduledEndTime     = $date->copy()->setTime(18, 0, 0);
            $scheduledWorkMinutes = 480;

            // Late, early leave, overtime
            $attendance->late_by = $checkInTime->gt($scheduledStartTime)
            ? $scheduledStartTime->diff($checkInTime)->format('%H:%I:%S')
            : null;

            $attendance->early_leave_by = $checkOutTime->lt($scheduledEndTime)
            ? $checkOutTime->diff($scheduledEndTime)->format('%H:%I:%S')
            : null;

            $attendance->overtime_minutes = max(0, $totalMinutes - $scheduledWorkMinutes);

            // Status
            $attendance->status        = $attendance->late_by ? 'Late' : 'Present';
            $attendance->total_minutes = $totalMinutes;
            $attendance->is_manual     = false;

            $attendance->save();

            Log::channel('attProsLog')->info("Attendance record updated for employee {$employee->id} on {$date->toDateString()}");

        } catch (\Exception $e) {
            Log::channel('attProsLog')->error(
                "Error processing attendance for employee {$employeeId} on {$date->toDateString()}: " .
                $e->getMessage() . "\n" . $e->getTraceAsString()
            );
            throw $e;
        }
    }


}
