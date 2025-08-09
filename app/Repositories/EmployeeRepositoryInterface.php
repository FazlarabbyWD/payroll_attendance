<?php
namespace App\Repositories;

use App\Models\Employee;

interface EmployeeRepositoryInterface
{

    public function getAllEmployees();
    public function getAllEmploymentTypes();
    public function getEmployeeStats();
    public function findEmployeeById(int $employeeId): ?Employee;
    public function createEmployee(array $data);
    public function updateEmployeeIdOnDevice($employee, $userid);
    public function addEmployeeAddress(Employee $employee, array $addressData);
    public function addEmployeePersonalInfo(Employee $employee, array $personalData);

    //  public function findEmployeeById($id);
    //   public function updateEmployee($id, array $data);
    //    public function deleteEmployee($id);

}
