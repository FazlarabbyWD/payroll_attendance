<?php
namespace App\Repositories;

use App\Models\Department;


interface DepartmentRepositoryInterface
{
    public function getAll();
    public function countDepartments();
    public function create(array $departmentData): Department;
    public function find(string $id): ?Department;
    public function update(Department $Department, array $DepartmentData): Department;
    public function delete(Department $Department): void;

    public function getGender();
    public function getReligion();
    public function getMaritalStatus();
    public function getBloodGroup();

}
