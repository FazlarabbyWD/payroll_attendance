<?php
namespace App\Repositories;

use App\Models\Device;
use App\Models\Employee;

interface DeviceRepositoryInterface
{
    public function getAll();
    public function create(array $deviceData): Device;
    public function find(string $id): ?Device;
    public function update(Device $Device, array $DeviceData): Device;
    public function delete(Device $Device): void;

    public function getActiveDeviceIp(): ?string;

    public function updateEmployeeDeviceId(Employee $employee, int $userid): bool;

    public function getAllActiveDeviceIps();

     public function syncEmployees(Device $device): int;

}
