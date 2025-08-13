<?php
namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Services\DeviceServiceInterface;
use Illuminate\Http\Request;

class DeviceDataSyncController extends Controller
{

    protected $deviceService;
    public function __construct(DeviceServiceInterface $deviceService)
    {
        $this->deviceService = $deviceService;

    }
    public function index()
    {
        $devices = $this->deviceService->getAllDevices()->load('syncLogs');

        return view('devices.data_sync', compact('devices'));
    }

    public function sync(Device $device, Request $request, DeviceServiceInterface $deviceService)
    {
        $type = $request->query('type');

        if ($type === 'employee') {
            try {
                $count = $deviceService->syncEmployees($device);
                return redirect()->back()->with('success', "{$count} employees synced.");
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        // For attendance sync, create a separate method later
    }

}
