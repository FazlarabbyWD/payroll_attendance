<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\AttendanceSyncService;

class SyncAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-attendance';

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
            $service = new AttendanceSyncService(); 
            $result = $service->syncAttendance();

            if ($result) {
                $this->info('Attendance sync completed successfully.');
            } else {
                $this->error('Attendance sync failed. See logs for details.');
            }

        } catch (\Exception $e) {
            Log::error('Attendance sync failed due to an exception: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            $this->error('Attendance sync failed. See logs for details.');
        }
    }
}
