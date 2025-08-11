<?php

namespace App\Repositories;

use App\Models\EmployeeEducation;
use Illuminate\Database\Eloquent\Collection;

interface EmployeeEducationRepositoryInterface
{
    public function getByEmployeeId(int $employeeId): Collection;
    public function find(int $id): ?EmployeeEducation;
    public function create(array $data): EmployeeEducation;
    public function update(int $id, array $data): ?EmployeeEducation;
    public function softDelete(int $id): bool;
    public function softDeleteMany(int $employeeId, array $idsToKeep): int; 
}
