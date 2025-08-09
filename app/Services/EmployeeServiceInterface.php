<?php
namespace App\Services;

use App\Http\Requests\EmployeeBasicInfoStoreRequet;
use App\Models\Employee;

interface EmployeeServiceInterface
{
    public function getAllEmploymentTypes();
    public function findEmployeeById(int $employeeId): ?Employee;

    public function createEmployee(EmployeeBasicInfoStoreRequet $request): Employee;
    public function addEmployeeToDevices(Employee $employee): bool;

    public function createAndAddToDevice($request): Employee;

    public function addEmployeePersonalInfo(Employee $employee, array $personalData): Employee;

    public function addEmployeeAddress(Employee $employee, array $addressData): Employee;

    public function savePersonalAndAddress(Employee $employee, array $personalData, array $addressData): Employee;
}
