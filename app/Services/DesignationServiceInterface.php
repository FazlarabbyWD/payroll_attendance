<?php
namespace App\Services;

use App\Http\Requests\DesignationStoreRequest;
use App\Http\Requests\DesignationUpdateRequest;
use App\Models\Designation;

interface DesignationServiceInterface
{
    public function getAllDesignations();

 

    public function createDesignation(DesignationStoreRequest $request): Designation;
    public function findDesignation(string $id): ?Designation;

    public function updateDesignation(DesignationUpdateRequest $request, string $id): Designation;

    public function deleteDesignation(string $id): void;

    public function getDesignationByDept(int $departmentId): array;

}
