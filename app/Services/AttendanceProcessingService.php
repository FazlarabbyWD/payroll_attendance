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

        $lastProcessed   = AttendanceProcess::where('type', self::ATTENDANCE_LOGS_TYPE)->first();
        $lastId          = $lastProcessed?->last_attendance_log_id ?? 0;
        $lastProcessedAt = $lastProcessed?->last_processed_at;

        $attendanceLogs = AttendanceLog::with('device')
            ->where('id', '>', $lastId)
            ->orderBy('id', 'asc')
            ->get();

        if ($attendanceLogs->isEmpty()) {
            Log::channel('attProsLog')->info("No new attendance logs found to process.");
            return;
        }

        $groupedLogs = $attendanceLogs->groupBy(function ($log) {
            return $log->employee_id . '|' . Carbon::parse($log->timestamp)->toDateString();
        });

        Log::channel('attProsLog')->info("Processing " . count($groupedLogs) . " employee-day groups.");

        DB::beginTransaction();

        try {
            foreach ($groupedLogs as $key => $logs) {
                list($employeeId, $dateString) = explode('|', $key);
                $date                          = Carbon::parse($dateString);

                $this->processEmployeeAttendance($employeeId, $date, $logs);
            }

            // Update last processed info
            $latestLog = $attendanceLogs->last();
            AttendanceProcess::updateOrCreate(
                ['type' => self::ATTENDANCE_LOGS_TYPE],
                [
                    'last_attendance_log_id' => $latestLog->id,
                    'last_processed_at'      => $latestLog->timestamp,
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

    protected function processEmployeeAttendance(string $employeeId, Carbon $date, $logs)
    {
        try {
            $employee = Employee::find($employeeId);
            if (! $employee) {
                Log::channel('attProsLog')->warning("Employee with ID {$employeeId} not found. Skipping.");
                return;
            }

            // Sort logs by timestamp
            $firstLog = $logs->sortBy('timestamp')->first();
            $lastLog  = $logs->sortByDesc('timestamp')->first();

            $currentCheckIn  = Carbon::parse($firstLog->timestamp);
            $currentCheckOut = Carbon::parse($lastLog->timestamp);

            // Get existing attendance or create new
            $attendance = Attendance::firstOrNew([
                'employee_id' => $employee->id,
                'date'        => $date->toDateString(),
            ]);

            // Update check-in/check-out dynamically
            $attendance->check_in = $attendance->check_in
            ? min($attendance->check_in, $currentCheckIn->toTimeString())
            : $currentCheckIn->toTimeString();

            $attendance->check_out = $attendance->check_out
            ? max($attendance->check_out, $currentCheckOut->toTimeString())
            : $currentCheckOut->toTimeString();

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

    // protected function processEmployeeAttendance(string $employeeId, Carbon $date, $logs)
    // {
    //     try {
    //         $employee = Employee::find($employeeId);
    //         if (!$employee) {
    //             Log::channel('attProsLog')->warning("Employee with ID {$employeeId} not found. Skipping.");
    //             return;
    //         }

    //         $firstLog = $logs->sortBy('timestamp')->first();
    //         $lastLog = $logs->sortByDesc('timestamp')->first();

    //         $checkInTime = Carbon::parse($firstLog->timestamp);
    //         $checkOutTime = Carbon::parse($lastLog->timestamp);

    //         $attendance = Attendance::where('employee_id', $employee->id)
    //             ->where('date', $date->toDateString())
    //             ->first();

    //         if ($attendance) {
    //             Log::channel('attProsLog')->info("Attendance already exists for employee {$employee->id} on {$date->toDateString()}. Skipping.");
    //             return;
    //         }

    //         $totalMinutes = $checkInTime->diffInMinutes($checkOutTime);

    //         // Hardcoded schedule (can be replaced with shift logic)
    //         $scheduledStartTime = $date->copy()->setTime(10, 0, 0);
    //         $scheduledEndTime = $date->copy()->setTime(18, 0, 0);
    //         $scheduledWorkMinutes = 480;

    //         $lateBy = null;
    //         $earlyLeaveBy = null;
    //         $overtimeMinutes = 0;
    //         $status = 'Present';

    //         if ($checkInTime->gt($scheduledStartTime)) {
    //             $lateBy = $scheduledStartTime->diff($checkInTime)->format('%H:%I:%S');
    //             $status = 'Late';
    //         }

    //         if ($checkOutTime->lt($scheduledEndTime)) {
    //             $earlyLeaveBy = $checkOutTime->diff($scheduledEndTime)->format('%H:%I:%S');
    //         }

    //         if ($totalMinutes > $scheduledWorkMinutes) {
    //             $overtimeMinutes = $totalMinutes - $scheduledWorkMinutes;
    //         }

    //         Attendance::create([
    //             'employee_id' => $employee->id,
    //             'date' => $date->toDateString(),
    //             'check_in' => $checkInTime->toTimeString(),
    //             'check_out' => $checkOutTime->toTimeString(),
    //             'total_minutes' => $totalMinutes,
    //             'late_by' => $lateBy,
    //             'early_leave_by' => $earlyLeaveBy,
    //             'overtime_minutes' => $overtimeMinutes,
    //             'status' => $status,
    //             'is_manual' => false,
    //         ]);

    //         Log::channel('attProsLog')->info("Attendance record created for employee {$employee->id} on {$date->toDateString()}");
    //     } catch (\Exception $e) {
    //         Log::channel('attProsLog')->error("Error processing attendance for employee {$employeeId} on {$date->toDateString()}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    //         throw $e;
    //     }
    // }
}
