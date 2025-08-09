<?php
namespace App\Services;

use App\Models\Employee;
use App\Http\Requests\EmployeeBasicInfoStoreRequet;

interface EmployeeServiceInterface
{
   public function getAllEmploymentTypes();

    public function createEmployee(EmployeeBasicInfoStoreRequet $request): Employee;
    public function addEmployeeToDevices(Employee $employee): bool;

    public function createAndAddToDevice($request): Employee;
}
