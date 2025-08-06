<?php
namespace App\Services;

use App\Http\Requests\DepartmentStoreRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use App\Models\Department;


interface DepartmentServiceInterface
{
     public function getAllDepartments();
    public function createDepartment(DepartmentStoreRequest $request): Department;
    public function findDepartment(string $id): ?Department;
    public function updateDepartment(DepartmentUpdateRequest $request, string $id): Department;

    public function deleteDepartment(string $id): void;

}

