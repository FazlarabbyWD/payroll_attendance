<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\AttendanceProcessingService;

class ProcessAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
   public function handle()
    {
        try {
            $service = new AttendanceProcessingService();
            $result = $service->processAttendanceLogs();

            if ($result) {
                $this->info('Attendance process completed successfully.');
            } else {
                $this->error('Attendance process failed. See logs for details.');
            }

        } catch (\Exception $e) {
            Log::error('Attendance process failed due to an exception: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            $this->error('Attendance process failed. See logs for details.');
        }
    }
}
