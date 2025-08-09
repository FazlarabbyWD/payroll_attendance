<?php
namespace App\Repositories;

use App\Models\Department;
use App\Models\Designation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DesignationRepository implements DesignationRepositoryInterface
{

    protected $designationCrudLog;

    public function __construct()
    {
        $this->designationCrudLog = Log::channel('designationStoreLog');
    }


    public function create(array $designationData): Designation
    {

        return DB::transaction(function () use ($designationData) {

            $designation              = new Designation();
            $designation->name        = $designationData['name'];
            $designation->description = $designationData['description'];
            $designation->created_by  = auth()->user()->id;

            $designation->save();

            if (! empty($designationData['department_ids'])) {
                $designation->departments()->sync($designationData['department_ids']);
            }

            $this->designationCrudLog->info('Device record inserted into DB', [
                'designation_id' => $designation->id,
                'name'           => $designation->name,
            ]);

            return $designation;
        });
    }

     public function update(Designation $designation, array $designationData): Designation
    {
        // dd($designationData);
        return DB::transaction(function () use ($designation, $designationData) {
            $designation->name        = $designationData['name'];
            $designation->description = $designationData['description'];
           $designation->updated_by  = auth()->user()->id;

            $designation->save();

            if (isset($designationData['department_ids'])) {
                $designation->departments()->sync($designationData['department_ids']);
            }

            $this->designationCrudLog->info('Designation record updated in DB', [
                'designation_id' => $designation->id,
                'name'           => $designation->name,
            ]);

            return $designation;
        });
    }

    public function delete(Designation $designation): void
    {
        DB::transaction(function () use ($designation) {
            $designation->departments()->detach();

            $designation->delete();

            $this->designationCrudLog->info('Designation record deleted from DB', [
                'designation_id' => $designation->id,
            ]);
        });
    }

    public function find(string $id): ?Designation
    {
        return Designation::with('departments')->find($id);
    }


    public function getDesignations()
    {
        return Designation::all();
    }

    public function getDesignationByDept(int $departmentId): array
    {
        $department = Department::with('designations')->find($departmentId);

        if (! $department) {
            return [];
        }

        return $department->designations->toArray();
    }

}
