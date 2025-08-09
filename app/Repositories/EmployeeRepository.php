<?php
namespace App\Repositories;

use App\Models\Employee;
use App\Models\EmploymentType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    protected $employeeStoreLog;

    public function __construct()
    {
        $this->employeeStoreLog = Log::channel('employeeStoreLog');
    }

    public function getAllEmploymentTypes()
    {
        return EmploymentType::all();
    }

    public function createEmployee(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $employee                     = new Employee();
                $employee->first_name         = $data['first_name'];
                $employee->last_name          = $data['last_name'];
                $employee->date_of_joining    = $data['date_of_joining'];
                $employee->employment_type_id = $data['employment_type_id'];
                $employee->department_id      = $data['department_id'];
                $employee->designation_id     = $data['designation_id'];
                $employee->created_by         = auth()->id();

                $employee->save();

                $this->employeeStoreLog->info('Employee record inserted into DB', [
                    'id'        => $employee->id,
                    'employee_name'      => $employee->first_name . ' ' . $employee->last_name,
                    'date_of_joining'    => $employee->date_of_joining,
                    'employment_type_id' => $employee->employment_type_id,
                    'department_id'      => $employee->department_id,
                    'designation_id'     => $employee->designation_id,
                ]);

                return $employee;
            });
        } catch (\Exception $e) {
            $this->employeeStoreLog->error('Error during Employee record insertion', [
                'error_message' => $e->getMessage(),
                'trace'         => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function updateEmployeeIdOnDevice($employee,$userid)
    {
        try {

            $employee->employee_id = $userid;
            $employee->save();

            $this->employeeStoreLog->info('Employee record updated in DB', [
                'employee_id' => $employee->id,
                'employee_employee_id' => $employee->employee_id,
            ]);
        } catch (\Exception $e) {
            $this->employeeStoreLog->error('Error during Employee record update', [
                'employee_id' => $employee->id,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
