<?php
namespace App\Services;

use App\Http\Requests\DeviceStoreRequest;
use App\Http\Requests\DeviceUpdateRequest;
use App\Models\Device;

interface DeviceServiceInterface
{
    public function createDevice(DeviceStoreRequest $request): Device;
    public function findDevice(string $id): ?Device;
    public function updateDevice(DeviceUpdateRequest $request, string $id): Device;

    public function deleteDevice(string $id): void;
}

