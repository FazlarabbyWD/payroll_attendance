<?php
namespace App\Repositories;

use App\Models\Device;
use App\Models\Employee;
use App\Models\DeviceSyncLog;
use Jmrashed\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeviceRepository implements DeviceRepositoryInterface
{
    protected $DeviceStoreLog;

    public function __construct()
    {
        $this->DeviceStoreLog = Log::channel('deviceStoreLog');
    }

    public function getAll()
    {
        return Device::all();
    }

    public function create(array $deviceData): Device
    {

        return DB::transaction(function () use ($deviceData) {

            $device              = new Device();
            $device->device_name = $deviceData['device_name'];
            $device->location    = $deviceData['location'];
            $device->ip_address  = $deviceData['ip_address'];
            $device->port        = $deviceData['port'];

            $device->created_by = auth()->user()->id;

            $device->save();

            $this->DeviceStoreLog->info('Device record inserted into DB', [
                'device_id'   => $device->id,
                'device_name' => $device->device_name,
                'location'    => $device->location,
                'ip_address'  => $device->ip_address,
            ]);

            return $device;
        });
    }
    public function find(string $id): ?Device
    {
        return Device::find($id);
    }

    public function update(Device $device, array $deviceData): Device
    {
        return DB::transaction(function () use ($device, $deviceData) {
            $device->device_name = $deviceData['device_name'];
            $device->location    = $deviceData['location'];
            $device->ip_address  = $deviceData['ip_address'];
            $device->port        = $deviceData['port'];
            $device->status      = $deviceData['status'];

            $device->updated_by = auth()->user()->id;

            $device->save();

            $this->DeviceStoreLog->info('Device record updated in DB', [
                'device_id'   => $device->id,
                'device_name' => $device->device_name,
                'location'    => $device->location,
                'ip_address'  => $device->ip_address,
            ]);

            return $device;
        });
    }

    public function delete(Device $device): void
    {
        DB::transaction(function () use ($device) {

            $device->deleted_by = auth()->user()->id;

            $device->delete();

            $this->DeviceStoreLog->info('Device record deleted from DB', [
                'device_id' => $device->id,
            ]);
        });
    }

    public function getActiveDeviceIp(): ?string
    {
        return Device::where('status', 1)->value('ip_address');
    }

    public function updateEmployeeDeviceId(Employee $employee, int $userid): bool
    {
        $employee->employee_id = $userid;
        return $employee->save();
    }

    public function getAllActiveDeviceIps()
    {
        return Device::where('status', 1)->pluck('ip_address');
    }

    /**
     * Sync employees from device
     *
     * @param Device $device
     * @return int Number of synced users
     * @throws \Exception
     */
 public function syncEmployees(Device $device, string $type = 'employee'): int
{
    $deviceIp = $device->ip_address;

    $zk = new ZKTeco($deviceIp);
    $connected = $zk->connect();

    if (!$connected) {
        throw new \Exception("Could not connect to device {$device->device_name} ({$deviceIp})");
    }

    $users = $zk->getUser();
    $count = 0;

    foreach ($users as $user) {
        if (empty($user['userid']) || empty($user['name'])) {
            continue;
        }

        Employee::updateOrCreate(
            ['employee_id' => $user['userid']],
            ['first_name' => $user['name']]
        );

        $count++;
    }


    DeviceSyncLog::create([
        'device_id' => $device->id,
        'type'      => $type,
        'last_sync' => now(),
    ]);

    $this->DeviceStoreLog->info("Synced {$count} {$type}s from device", [
        'device_id' => $device->id,
        'ip' => $device->ip_address
    ]);

    return $count;
}


}
