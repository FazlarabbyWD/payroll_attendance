<?php
namespace App\Services;

use App\Http\Requests\DepartmentStoreRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use App\Models\Department;


interface DepartmentServiceInterface
{
     public function getAllDepartments();
    public function createDepartments(DepartmentStoreRequest $request): Department;
    public function findDepartments(string $id): ?Department;
    public function updateDepartments(DepartmentUpdateRequest $request, string $id): Department;

    public function deleteDepartments(string $id): void;
}

