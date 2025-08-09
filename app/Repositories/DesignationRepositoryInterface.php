<?php
namespace App\Repositories;

use App\Models\Designation;

interface DesignationRepositoryInterface
{

    public function create(array $designationData): Designation;
    public function find(string $id): ?Designation;
    public function getDesignations();
    public function update(Designation $designation, array $designationData): Designation;
    public function delete(Designation $designation): void;

    public function getDesignationByDept(int $departmentId): array;

}
