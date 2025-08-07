<?php
namespace App\Services;

use App\Models\Designation;
use App\Http\Requests\DesignationStoreRequest;
use App\Http\Requests\DesignationUpdateRequest;

interface DesignationServiceInterface
{
    public function getAllDesignations();
    public function getAllDepartments();
    public function createDesignation(DesignationStoreRequest $request): Designation;
    public function findDesignation(string $id): ?Designation;

    public function updateDesignation(DesignationUpdateRequest $request, string $id): Designation;

    public function deleteDesignation(string $id): void;

}
