<?php
namespace App\Repositories;

use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    protected $DepartmentStoreLog;

    public function __construct()
    {
        $this->DepartmentStoreLog = Log::channel('departmentStoreLog');
    }

    public function getAll()
    {
        return Department::all();
    }

    public function create(array $departmentData): Department
    {

        return DB::transaction(function () use ($departmentData) {


            $department              = new Department();
            $department->name = $departmentData['name'];
            $department->description = $departmentData['description'];
            $department->created_by = auth()->user()->id;
            $department->save();

            $this->DepartmentStoreLog->info('Department record inserted into DB', [
                'department_id'   => $department->id,
                'department_name' => $department->name,
                'description'    => $department->description,

            ]);

            return $department;
        });
    }
    public function find(string $id): ?Department
    {
        return Department::find($id);
    }


       public function update(Department $department, array $departmentData): Department
    {
        return DB::transaction(function () use ($department, $departmentData) {
            $department->name        = $departmentData['name'];
            $department->description = $departmentData['description'];
            $department->updated_by  = auth()->user()->id;
            $department->save();

            $this->DepartmentStoreLog->info('Department record updated in DB', [
                'department_id'   => $department->id,
                'department_name' => $department->name,
                'description'     => $department->description,
            ]);

            return $department;
        });
    }

    public function delete(Department $department): void
    {
        DB::transaction(function () use ($department) {
            $department->deleted_by = auth()->user()->id;
            $department->delete();

            $this->DepartmentStoreLog->info('Department record deleted from DB', [
                'department_id' => $department->id,
            ]);
        });
    }

}
