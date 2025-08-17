<?php

namespace App\Services;

use App\Models\AttendanceLog;
use App\Models\Device;
use App\Models\DeviceSyncLog;
use Jmrashed\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Facades\Log;

class AttendanceSyncService
{
    public function syncAttendance()
    {
        $devices = Device::where('status', 1)->get();

        foreach ($devices as $device) {
            $this->syncDeviceAttendance($device);
        }
    }

     protected function syncDeviceAttendance(Device $device)
    {
        Log::channel('attSyncLog')->info("Attempting to sync device: {$device->ip_address} (ID: {$device->id})");

        try {
            $zk = new ZKTeco($device->ip_address, $device->port ?? 4370);

            if (! $zk->connect()) {
                Log::channel('attSyncLog')->warning("Failed to connect to device: {$device->ip_address}");
                return;
            }

            $attendanceLogs = collect($zk->getAttendance());
            Log::channel('attSyncLog')->info("Device {$device->id} ({$device->ip_address}) total logs fetched: " . count($attendanceLogs));

            if ($attendanceLogs->isEmpty()) {
                Log::channel('attSyncLog')->info("No logs found for device {$device->id}");
                $zk->disconnect();
                return;
            }

            $syncLog = DeviceSyncLog::firstOrCreate(
                ['device_id' => $device->id, 'type' => 'attendance'],
                ['last_sync' => null]
            );

            Log::channel('attSyncLog')->info("Device {$device->id} last_sync: " . $syncLog->last_sync);

            $newLogs = $attendanceLogs->filter(function ($log) use ($syncLog) {
                return $syncLog->last_sync === null || $log['timestamp'] > $syncLog->last_sync;
            });

            Log::channel('attSyncLog')->info("Device {$device->id} new logs after last_sync: " . count($newLogs));

            if ($newLogs->isEmpty()) {
                Log::channel('attSyncLog')->info("No new logs to insert for device {$device->id}");
                $zk->disconnect();
                return;
            }

            $this->insertAttendanceLogs($device, $newLogs);

            $latestLogTime = $newLogs->max('timestamp');
            $syncLog->update(['last_sync' => $latestLogTime]);
            Log::channel('attSyncLog')->info("Device {$device->id} last_sync updated to: " . $latestLogTime);

            $zk->disconnect();

        } catch (\Exception $e) {
            Log::channel('attSyncLog')->error("Error syncing device {$device->ip_address}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }

    protected function insertAttendanceLogs(Device $device, $logs)
    {
        $logs->chunk(500)->each(function ($chunk) use ($device) {
            $insertData = [];
            foreach ($chunk as $log) {
                $insertData[] = [
                    'device_id'   => $device->id,
                    'employee_id' => $log['id'],
                    'timestamp'   => $log['timestamp'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }

            if (! empty($insertData)) {
                AttendanceLog::insert($insertData);
            }
        });

        Log::channel('attSyncLog')->info("Inserted " . count($logs) . " new attendance logs for device {$device->id}");
    }
}
