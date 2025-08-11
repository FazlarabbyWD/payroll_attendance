<?php
namespace App\Repositories;

use App\Models\EmployeeEducation;
use Illuminate\Database\Eloquent\Collection;

class EmployeeEducationRepository implements EmployeeEducationRepositoryInterface
{
    public function getByEmployeeId(int $employeeId): Collection
    {
        return EmployeeEducation::where('employee_id', $employeeId)->get();
    }

    public function find(int $id): ?EmployeeEducation
    {
        return EmployeeEducation::find($id);
    }

    public function create(array $data): EmployeeEducation
    {
        return EmployeeEducation::create($data);
    }

    public function update(int $id, array $data): ?EmployeeEducation
    {
        $education = $this->find($id);
        if ($education) {
            $education->update($data);
        }
        return $education;
    }

    public function softDelete(int $id): bool
    {
        $education = $this->find($id);
        if ($education) {
            return $education->delete(); // This performs soft delete due to trait
        }
        return false;
    }

    /**
     * Soft deletes education records for a given employee that are NOT in the $idsToKeep array.
     *
     * @param int $employeeId
     * @param array $idsToKeep
     * @return int The number of soft deleted records.
     */
    public function softDeleteMany(int $employeeId, array $idsToKeep): int
    {
        return EmployeeEducation::where('employee_id', $employeeId)
            ->whereNotIn('id', $idsToKeep)
            ->delete();
    }
}
