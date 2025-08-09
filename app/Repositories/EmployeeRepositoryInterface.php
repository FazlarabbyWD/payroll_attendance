<?php
namespace App\Repositories;

interface EmployeeRepositoryInterface
{

    public function getAllEmploymentTypes();
    public function createEmployee(array $data);
    public function updateEmployeeIdOnDevice($employee, $userid);
    // public function getAllEmployees();
    //  public function findEmployeeById($id);
    //   public function updateEmployee($id, array $data);
    //    public function deleteEmployee($id);

}
