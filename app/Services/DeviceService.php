<?php
namespace App\Services;

use App\Exceptions\DeviceCreationException;
use App\Exceptions\DeviceDeletionException;
use App\Exceptions\DeviceNotFoundException;
use App\Exceptions\DeviceUpdateException;
use App\Http\Requests\DeviceStoreRequest;
use App\Http\Requests\DeviceUpdateRequest;
use App\Models\Device;
use App\Repositories\DeviceRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DeviceService implements DeviceServiceInterface
{
    protected $deviceRepository;
    protected $deviceCrudLog;

    public function __construct(DeviceRepositoryInterface $deviceRepository)
    {
        $this->deviceRepository = $deviceRepository;
        $this->deviceCrudLog    = Log::channel('deviceStoreLog');
    }

    public function getAllDevices()
    {
        return $this->deviceRepository->getAll();
    }

    public function createDevice(DeviceStoreRequest $request): Device
    {
        $maxRetries = Config::get('device.max_retries', 3);
        $retryDelay = Config::get('device.initial_retry_delay_ms', 100);

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {

            $this->deviceCrudLog->info('Attempting Device creation', [
                'attempt' => $attempt,
                //'device_name' => $device, // Removed this line
            ]);

            try {
                $deviceData = $request->validated();

                $device = $this->deviceRepository->create($deviceData);

                $this->deviceCrudLog->info('Device created', [
                    'attempt'     => $attempt,
                    'device_id'   => $device->id,
                    'device_name' => $device->device_name,
                ]);

                return $device;

            } catch (QueryException $e) {
                if ($this->isDeadlockOrConnectionException($e)) {
                    $this->deviceCrudLog->warning('Transient DB issue during user creation, retrying...', [
                        'attempt'       => $attempt,

                        'error_message' => $e->getMessage(),
                    ]);
                    usleep($retryDelay * 1000);
                    $retryDelay *= 2;
                    continue;
                }

                $this->deviceCrudLog->error('Query exception during user creation', [
                    'attempt'       => $attempt,

                    'error_message' => $e->getMessage(),
                ]);

                throw $e;
            } catch (\Exception $e) {
                $this->deviceCrudLog->error('Unexpected error during user creation', [
                    'attempt'       => $attempt,

                    'error_message' => $e->getMessage(),
                ]);

                throw $e;
            }
        }

        throw new DeviceCreationException("Failed to create Device after {$maxRetries} attempts.");
    }

    public function findDevice(string $id): ?Device
    {
        try {
            $device = $this->deviceRepository->find($id);

            if (! $device) {
                $this->deviceCrudLog->warning("device not found", ['device_id' => $id]);
                throw new DeviceNotFoundException("device with ID {$id} not found.");
            }

            return $device;
        } catch (\Exception $e) {
            $this->deviceCrudLog->error("Error finding device", [
                'device_id'     => $id,
                'error_message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function updateDevice(DeviceUpdateRequest $request, string $id): Device
    {
        try {
            $device = $this->findDevice($id);

            $deviceData = $request->validated();

            $device = $this->deviceRepository->update($device, $deviceData);

            $this->deviceCrudLog->info('Device updated successfully', [
                'device_id' => $device->id,
            ]);

            return $device;

        } catch (DeviceNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->deviceCrudLog->error("Failed to update Device", [
                'device_id'     => $id,
                'error_message' => $e->getMessage(),
            ]);
            throw new DeviceUpdateException("Failed to update Device: " . $e->getMessage(), 0, $e);
        }
    }

    public function deleteDevice(string $id): void
    {
        try {
            $device = $this->findDevice($id);

            $this->deviceRepository->delete($device);

            $this->deviceCrudLog->info("Device deleted successfully", ['device_id' => $id]);

        } catch (DeviceNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->deviceCrudLog->error("Failed to delete device", [
                'device_id'     => $id,
                'error_message' => $e->getMessage(),
            ]);
            throw new DeviceDeletionException("Failed to delete device: " . $e->getMessage(), 0, $e);
        }
    }

    protected function isDeadlockOrConnectionException(\Exception $e): bool
    {
        $message = $e->getMessage();
        $code    = $e->getCode();

        return Str::contains($message, [
            'Deadlock found when trying to get lock',
            'Lock wait timeout exceeded',
            'SQLSTATE[HY000] [2002]',
            'Connection refused',
        ]) || in_array($code, ['40001', '40P01']);
    }
}
