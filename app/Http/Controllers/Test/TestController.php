<?php
namespace App\Http\Controllers\Test;

use App\Models\Device;
use App\Models\AttendanceLog;
use App\Models\DeviceSyncLog;
use Jmrashed\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\AttendanceProcessingService;

class TestController extends Controller
{


    protected $attProsService;
    public function __construct(AttendanceProcessingService $attProsService)
    {
        $this->attProsService = $attProsService;
    }

     public function index()
    {

        $checkAtt=$this->attProsService->processAttendanceLogs();

        dd($checkAtt);

        // $zk        = new ZKTeco('10.0.0.16');
        // $connected = $zk->connect();
        // if ($connected) {
        //    $attendanceLog = $zk->getAttendance();
        //     dd($attendanceLog);
        // }

        // $devices = Device::where('status', 1)->get();

        // $results = [];

        // foreach ($devices as $device) {
        //     $zk = new ZKTeco($device->ip_address, $device->port ?? 4370);
        //     $connected = $zk->connect();

        //     $results[] = [
        //         'device_name' => $device->device_name ?? $device->ip_address,
        //         'ip' => $device->ip_address,
        //         'port' => $device->port ?? 4370,
        //         'connected' => $connected ? 'Yes' : 'No',
        //     ];

        //     $zk->disconnect();
    }


    public function syncAttendance()
    {
        $devices = Device::where('status', 1)->get();

        foreach ($devices as $device) {
            try {
                $zk = new ZKTeco($device->ip_address, $device->port ?? 4370);

                if (! $zk->connect()) {
                    Log::warning("Failed to connect to device: {$device->ip_address}");
                    continue;
                }

                $attendanceLogs = collect($zk->getAttendance());
                Log::info("Device {$device->id} ({$device->ip_address}) total logs fetched: " . count($attendanceLogs));

                if ($attendanceLogs->isEmpty()) {
                    Log::info("No logs found for device {$device->id}");
                    $zk->disconnect();
                    continue;
                }

                // Ensure device_sync_logs record exists
                $syncLog = DeviceSyncLog::firstOrCreate(
                    ['device_id' => $device->id, 'type' => 'attendance'],
                    ['last_sync' => null]
                );

                Log::info("Device {$device->id} last_sync: " . $syncLog->last_sync);

                // Filter only logs after last sync
                $newLogs = $attendanceLogs->filter(function ($log) use ($syncLog) {
                    return $syncLog->last_sync === null || $log['timestamp'] > $syncLog->last_sync;
                });

                Log::info("Device {$device->id} new logs after last_sync: " . count($newLogs));

                if ($newLogs->isEmpty()) {
                    Log::info("No new logs to insert for device {$device->id}");
                    $zk->disconnect();
                    continue;
                }

                // Bulk insert in chunks
                $newLogs->chunk(500)->each(function ($chunk) use ($device) {
                    $insertData = [];
                    foreach ($chunk as $log) {
                        $insertData[] = [
                            'device_id'   => $device->id,
                            'employee_id' => $log['id'], // raw device ID
                            'timestamp'   => $log['timestamp'],
                            'created_at'  => now(),
                            'updated_at'  => now(),
                        ];
                    }

                    if (! empty($insertData)) {
                        AttendanceLog::insert($insertData);
                    }
                });

                // Update last_sync to the latest timestamp from inserted logs
                $latestLogTime = $newLogs->max('timestamp');
                $syncLog->update(['last_sync' => $latestLogTime]);
                Log::info("Device {$device->id} last_sync updated to: " . $latestLogTime);

                $zk->disconnect();

            } catch (\Exception $e) {
                Log::error("Error syncing device {$device->ip_address}: " . $e->getMessage());
            }
        }

    }

    // public function syncAttendance()
    // {
    //     $devices = Device::where('status', 1)->get();

    //     foreach ($devices as $device) {
    //         try {
    //             $zk = new ZKTeco($device->ip_address, $device->port ?? 4370);

    //             if (! $zk->connect()) {
    //                 \Log::warning("Failed to connect to device: {$device->ip_address}");
    //                 continue;
    //             }

    //             $attendanceLogs = collect($zk->getAttendance());

    //             if ($attendanceLogs->isEmpty()) {
    //                 $zk->disconnect();
    //                 continue;
    //             }

    //             // Ensure device_sync_logs record exists (for attendance type)
    //             $syncLog = DeviceSyncLog::firstOrCreate(
    //                 ['device_id' => $device->id, 'type' => 'attendance'],
    //                 ['last_sync' => null]
    //             );

    //             // Filter only logs after last sync
    //             $newLogs = $attendanceLogs->filter(function ($log) use ($syncLog) {
    //                 return $syncLog->last_sync === null || $log['timestamp'] > $syncLog->last_sync;
    //             });

    //             if ($newLogs->isEmpty()) {
    //                 \Log::info("No new logs for device: {$device->ip_address}");
    //                 $zk->disconnect();
    //                 continue;
    //             }

    //             // Bulk insert in chunks
    //             $newLogs->chunk(500)->each(function ($chunk) use ($device) {
    //                 $insertData = [];

    //                 foreach ($chunk as $log) {
    //                     $insertData[] = [
    //                         'device_id'   => $device->id,
    //                         'employee_id' => $log['id'],
    //                         'timestamp'   => $log['timestamp'],
    //                         'created_at'  => now(),
    //                         'updated_at'  => now(),
    //                     ];
    //                 }

    //                 AttendanceLog::insert($insertData);
    //             });

    //             // Update last_sync to the latest timestamp from inserted logs
    //             $latestLogTime = $newLogs->max('timestamp');
    //             $syncLog->update(['last_sync' => $latestLogTime]);

    //             $zk->disconnect();

    //         } catch (\Exception $e) {
    //             \Log::error("Error syncing device {$device->ip_address}: " . $e->getMessage());
    //         }
    //     }
    // }

    // public function index()
    // {
    //     $device = Device::where('status', 1)->first();
    //     $zk     = new ZKTeco($device->ip_address);
    //     $zk->connect();

    //     $attendanceLog = $zk->getAttendance();

    //     $chunkSize = 500; // adjust based on your memory/server

    //     collect($attendanceLog)->chunk($chunkSize)->each(function ($chunk) use ($device) {
    //         $insertData = [];

    //         foreach ($chunk as $log) {
    //             $insertData[] = [
    //                 'uid'             => $log['uid'],
    //                 'device_user_id'  => $log['id'],
    //                 'state'           => $log['state'],
    //                 'attendance_time' => $log['timestamp'],
    //                 'type'            => $log['type'],
    //                 'device_ip'       => $device->ip_address,
    //                 'created_at'      => now(),
    //             ];
    //         }

    //         // Bulk insert for the chunk
    //         \DB::table('raw_attendance_log')->insert($insertData);
    //     });

    //     $zk->disconnect();
    // }



}
