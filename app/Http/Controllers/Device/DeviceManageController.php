<?php
namespace App\Http\Controllers\Device;

use App\Http\Requests\DeviceUpdateRequest;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Services\DeviceServiceInterface;
use App\Http\Requests\DeviceStoreRequest;
use App\Exceptions\DeviceNotFoundException;

class DeviceManageController extends Controller
{
    protected $deviceService;
    protected $deviceCrudLog;

    public function __construct(DeviceServiceInterface $deviceService)
    {
        $this->deviceService = $deviceService;
        $this->deviceCrudLog = Log::channel('deviceStoreLog');
    }
    public function index()
    {
        $devices = Device::all();

        return view('devices.index', compact('devices'));
    }

   public function store(DeviceStoreRequest $request): RedirectResponse
{
    $deviceName = $request->input('device_name');

    $this->deviceCrudLog->info('Received Device creation request', ['device_name' => $deviceName]);

    try {
        $device = $this->deviceService->createDevice($request);

        $this->deviceCrudLog->info('Device created successfully', [
            'device_name' => $device->device_name,
            'device_id'   => $device->id,
        ]);

        return redirect()->route('devices.index')->with('success', 'Device created successfully!');
    } catch (\Exception $e) {
        $this->deviceCrudLog->error('Failed to create device', [
            'device_name'   => $deviceName,
            'error_message' => $e->getMessage(),
            'trace'         => $e->getTraceAsString(),
        ]);

        return redirect()->back()->withInput()->withErrors([
            'error' => 'Failed to create device: ' . $e->getMessage(),
        ]);
    }
}


  public function edit(string $id)
{

    try {
        $device = $this->deviceService->findDevice($id);

        return view('devices.edit', compact('device'));

    } catch (DeviceNotFoundException $e) {
        return redirect()->route('devices.index')->withErrors([
            'error' => $e->getMessage(),
        ]);

    } catch (\Exception $e) {
        $this->deviceCrudLog->error("Failed to retrieve device for editing", [
            'device_id' => $id,
            'error_message' => $e->getMessage(),
        ]);

        return redirect()->route('devices.index')->withErrors([
            'error' => 'Failed to retrieve device for editing: ' . $e->getMessage(),
        ]);
    }
}

    public function update(DeviceUpdateRequest $request)
    {
        $deviceId = $request->input('device_id');

        try {
            $device = $this->deviceService->findDevice($deviceId);

            if (!$device) {
                throw new DeviceNotFoundException("Device with ID {$deviceId} not found.");
            }

            $updatedDevice = $this->deviceService->updateDevice($request, $deviceId);

            $this->deviceCrudLog->info('Device updated successfully', [
                'device_id' => $updatedDevice->id,
                'device_name' => $updatedDevice->device_name,
            ]);

            return redirect()->route('devices.index')->with('success', 'Device updated successfully!');

        } catch (DeviceNotFoundException $e) {
            return redirect()->route('devices.index')->withErrors([
                'error' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            $this->deviceCrudLog->error('Failed to update device', [
                'device_id' => $deviceId,
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->back()->withInput()->withErrors([
                'error' => 'Failed to update device: ' . $e->getMessage(),
            ]);
        }

    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $device = $this->deviceService->findDevice($id);

            if (!$device) {
                throw new DeviceNotFoundException("Device with ID {$id} not found.");
            }

            $this->deviceService->deleteDevice($id);

            $this->deviceCrudLog->info('Device deleted successfully', [
                'device_id' => $id,
                'device_name' => $device->device_name,
            ]);

            return redirect()->route('devices.index')->with('success', 'Device deleted successfully!');

        } catch (DeviceNotFoundException $e) {
            return redirect()->route('devices.index')->withErrors([
                'error' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            $this->deviceCrudLog->error('Failed to delete device', [
                'device_id' => $id,
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->back()->withErrors([
                'error' => 'Failed to delete device: ' . $e->getMessage(),
            ]);
        }
    }
 
}
