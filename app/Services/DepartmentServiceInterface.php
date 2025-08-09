<?php
namespace App\Services;

use App\Http\Requests\DepartmentStoreRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use App\Models\Department;

interface DepartmentServiceInterface
{
    public function getAllDepartments();
    public function getGender();
    public function getReligion();
    public function getMaritalStatus();
    public function getBloodGroup();
    public function createDepartment(DepartmentStoreRequest $request): Department;
    public function findDepartment(string $id): ?Department;
    public function updateDepartment(DepartmentUpdateRequest $request, string $id): Department;

    public function deleteDepartment(string $id): void;

}
