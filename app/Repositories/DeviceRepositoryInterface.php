<?php
namespace App\Repositories;

use App\Models\Device;

interface DeviceRepositoryInterface
{
    public function create(array $deviceData): Device;
    public function find(string $id): ?Device;
    public function update(Device $Device, array $DeviceData): Device;
    public function delete(Device $Device): void;

}
