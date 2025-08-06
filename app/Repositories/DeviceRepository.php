<?php
namespace App\Repositories;

use App\Models\Device;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeviceRepository implements DeviceRepositoryInterface
{
    protected $DeviceStoreLog;

    public function __construct()
    {
        $this->DeviceStoreLog = Log::channel('deviceStoreLog');
    }

    public function create(array $deviceData): Device
    {
        // dd($deviceData); // Debugging line, remove in production
        return DB::transaction(function () use ($deviceData) {

            // dd($deviceData); // Debugging line, remove in production

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
        $device->status        = $deviceData['status'];

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

}
