<?php
namespace App\Repositories;

use App\Models\Employee;

interface EmployeeRepositoryInterface
{

    public function getAllEmployees();
    public function getAllEmploymentTypes();
    public function getEmployeeStats();
    public function findEmployeeById($employee): ?Employee;
    public function createEmployee(array $data);
    public function updateEmployeeIdOnDevice($employee, $userid,$uid);
    public function addEmployeeAddress(Employee $employee, array $addressData);
    public function addEmployeePersonalInfo(Employee $employee, array $personalData);
    public function updateEmployee($employee, array $data);

    //  public function findEmployeeById($id);

       public function deleteEmployee(Employee $employee);

}
