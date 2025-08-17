<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\AttendanceProcess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\EmployeeShiftAssignment;

class AttendanceProcessingService
{
    /**
     * Process logs into daily attendance
     *
     * @param string|null $date  (default today)
     */
    // public function process(string $date = null)
    // {
    //     $date = $date ?? today()->toDateString();

    //     // Get all logs for this date
    //     $logs = AttendanceLog::whereDate('timestamp', $date)
    //         ->orderBy('timestamp')
    //         ->get()
    //         ->groupBy('employee_id');

    //     foreach ($logs as $employeeId => $employeeLogs) {
    //         $this->processEmployeeLogs($employeeId, $employeeLogs, $date);
    //     }
    // }

    // /**
    //  * Process logs for a single employee
    //  */
    // protected function processEmployeeLogs($employeeId, $logs, $date)
    // {
    //     $checkIn  = $logs->first()->timestamp;
    //     $checkOut = $logs->last()->timestamp;

    //     $totalMinutes = Carbon::parse($checkIn)->diffInMinutes(Carbon::parse($checkOut));

    //     // Get employee shift for the date
    //     $shift = EmployeeShiftAssignment::with('shift')
    //         ->where('employee_id', $employeeId)
    //         ->where('start_date', '<=', $date)
    //         ->where(function ($q) use ($date) {
    //             $q->whereNull('end_date')->orWhere('end_date', '>=', $date);
    //         })
    //         ->latest('start_date')
    //         ->first();

    //     $lateBy = null;
    //     $earlyLeaveBy = null;
    //     $overtimeMinutes = 0;

    //     if ($shift) {
    //         $shiftData = $shift->shift;

    //         $scheduledStart = Carbon::parse($date . ' ' . $shiftData->start_time);
    //         $scheduledEnd   = Carbon::parse($date . ' ' . $shiftData->end_time);

    //         // Handle night shifts (crossing midnight)
    //         if ($shiftData->is_night_shift && $scheduledEnd->lt($scheduledStart)) {
    //             $scheduledEnd->addDay();
    //         }

    //         // Late calculation
    //         if (Carbon::parse($checkIn)->gt($scheduledStart->copy()->addMinutes($shiftData->grace_period))) {
    //             $lateBy = $scheduledStart->diff(Carbon::parse($checkIn))->format('%H:%I:%S');
    //         }

    //         // Early leave
    //         if (Carbon::parse($checkOut)->lt($scheduledEnd)) {
    //             $earlyLeaveBy = Carbon::parse($checkOut)->diff($scheduledEnd)->format('%H:%I:%S');
    //         }

    //         // Overtime
    //         if (Carbon::parse($checkOut)->gt($scheduledEnd)) {
    //             $overtimeMinutes = $scheduledEnd->diffInMinutes(Carbon::parse($checkOut));
    //         }
    //     }

    //     // Insert or update attendance
    //     Attendance::updateOrCreate(
    //         ['employee_id' => $employeeId, 'date' => $date],
    //         [
    //             'check_in'         => $checkIn,
    //             'check_out'        => $checkOut,
    //             'total_minutes'    => $totalMinutes,
    //             'late_by'          => $lateBy,
    //             'early_leave_by'   => $earlyLeaveBy,
    //             'overtime_minutes' => $overtimeMinutes,
    //             'status'           => 'Present',
    //             'is_manual'        => false,
    //         ]
    //     );

    //     Log::info("Processed attendance for employee {$employeeId} on {$date}");
    // }


     const ATTENDANCE_LOGS_TYPE = 'attendance_logs';

    /**
     * Processes attendance logs to generate daily attendance records, skipping previously processed logs.
     */
    public function processAttendanceLogs()
    {
        Log::info("Starting attendance processing...");

        // Get the last processed timestamp from the database
        $lastProcessed = AttendanceProcess::where('type', self::ATTENDANCE_LOGS_TYPE)->first();
        $lastProcessedAt = $lastProcessed ? $lastProcessed->last_processed_at : null;

        $query = AttendanceLog::query();

        if ($lastProcessedAt) {
            $query->where('timestamp', '>', $lastProcessedAt);
        }

        //Eager load the device relation in advance to reduce query times
        $attendanceLogs = $query->with('device')->orderBy('timestamp', 'asc')->get();

        if ($attendanceLogs->isEmpty()) {
            Log::info("No new attendance logs found to process.");
            return;
        }

        // Group logs by employee and date for efficient processing
        $groupedLogs = $attendanceLogs->groupBy(function ($log) {
            return $log->employee_id . '|' . Carbon::parse($log->timestamp)->toDateString();
        });

        Log::info("Processing " . count($groupedLogs) . " employee-day groups.");

        DB::beginTransaction();

        try {
            $latestProcessedAt = $lastProcessedAt;  // Initialize with the previous value

            foreach ($groupedLogs as $key => $logs) {
                list($employeeId, $dateString) = explode('|', $key);
                $date = Carbon::parse($dateString);

                $this->processEmployeeAttendance($employeeId, $date, $logs);

                // Update the latest processed timestamp
                $latestLogTimestamp = $logs->max(fn($log) => $log->timestamp); // Find max timestamp in the group
                $latestProcessedAt = max($latestProcessedAt, $latestLogTimestamp);
            }

            // Update the last processed timestamp in the database
            AttendanceProcess::updateOrCreate(
                ['type' => self::ATTENDANCE_LOGS_TYPE],
                ['last_processed_at' => $latestProcessedAt]
            );

            DB::commit();
            Log::info("Attendance processing completed successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error processing attendance: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            throw $e; // Re-throw to allow for error handling upstream
        }
    }

    /**
     * Processes attendance logs for a single employee on a specific date.
     *
     * @param string $employeeId
     * @param Carbon $date
     * @param \Illuminate\Support\Collection $logs  Collection of AttendanceLog models.
     */
    protected function processEmployeeAttendance(string $employeeId, Carbon $date, $logs)
    {
        try {
            // Attempt to find the employee.  Crucial for foreign key integrity.
            $employee = Employee::find($employeeId);
            if (!$employee) {
                Log::warning("Employee with ID {$employeeId} not found. Skipping.");
                return;
            }

            $firstLog = $logs->sortBy('timestamp')->first();
            $lastLog = $logs->sortByDesc('timestamp')->first();

            $checkInTime = Carbon::parse($firstLog->timestamp);
            $checkOutTime = Carbon::parse($lastLog->timestamp);

            // Check if an attendance record already exists for this employee and date
            $attendance = Attendance::where('employee_id', $employee->id)->where('date', $date->toDateString())->first();

            if ($attendance) {
                Log::info("Attendance record already exists for employee {$employee->id} on {$date->toDateString()}.  Skipping.");
                return; // Skip if record already exists
            }

            $totalMinutes = $checkInTime->diffInMinutes($checkOutTime);
            $scheduledStartTime = $date->copy()->setTime(10, 0, 0); // Example: 9:00 AM
            $scheduledEndTime = $date->copy()->setTime(18, 0, 0); // Example: 5:00 PM (17:00)
            $scheduledWorkMinutes = 480; // 8 hours

            $lateBy = null;
            $earlyLeaveBy = null;
            $overtimeMinutes = 0;
            $status = 'Present';

            if ($checkInTime->gt($scheduledStartTime)) {
                $lateBy = $scheduledStartTime->diff($checkInTime)->format('%H:%I:%S');
                $status = 'Late';
            }

            if ($checkOutTime->lt($scheduledEndTime)) {
                $earlyLeaveBy = $checkOutTime->diff($scheduledEndTime)->format('%H:%I:%S');
            }

            if ($totalMinutes > $scheduledWorkMinutes) {
                $overtimeMinutes = $totalMinutes - $scheduledWorkMinutes;
            }

             // Determine absence.  Adjust thresholds as needed.
             if ($totalMinutes < 60 && $lateBy == null && $earlyLeaveBy == null) {
                $status = 'Absent';
            }

            Attendance::create([
                'employee_id' => $employee->id,
                'date' => $date->toDateString(),
                'check_in' => $checkInTime->toTimeString(),
                'check_out' => $checkOutTime->toTimeString(),
                'total_minutes' => $totalMinutes,
                'late_by' => $lateBy,
                'early_leave_by' => $earlyLeaveBy,
                'overtime_minutes' => $overtimeMinutes,
                'status' => $status,
                'is_manual' => false, // Assuming these are all from device logs
            ]);

            Log::info("Attendance record created for employee {$employee->id} on {$date->toDateString()}");

        } catch (\Exception $e) {
            Log::error("Error processing attendance for employee {$employeeId} on {$date->toDateString()}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            throw $e; //Re-throw so the parent try-catch block can handle the exception
        }
    }





}
