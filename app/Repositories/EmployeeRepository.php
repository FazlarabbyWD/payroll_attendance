<?php
namespace App\Repositories;

use App\Models\Employee;
use App\Models\EmployeeAddress;
use App\Models\EmploymentType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    protected $employeeStoreLog;

    public function __construct()
    {
        $this->employeeStoreLog = Log::channel('employeeStoreLog');
    }

    public function getAllEmployees()
    {

        // Redis::del('all_employees');

        $cacheKey = 'all_employees';

        $cached = Redis::get($cacheKey);
        if ($cached !== null) {
            return unserialize($cached);
        }

        $employees = Employee::with('department', 'designation', 'employmentStatus', 'bloodGroup')->orderBy('employee_id', 'asc')->paginate(200);

        Redis::setex($cacheKey, 300, serialize($employees));

        return $employees;
    }

    public function getEmployeeStats()
    {

        $cacheKey  = 'employee_stats';
        $cacheTime = 60 * 60;
        return Cache::remember($cacheKey, $cacheTime, function () {
            return Employee::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN phone_no IS NOT NULL AND national_id IS NOT NULL THEN 1 ELSE 0 END) as verified,
            SUM(CASE WHEN phone_no IS NULL AND national_id IS NULL THEN 1 ELSE 0 END) as pending
        ")->first();
        });
    }

    public function getAllEmploymentTypes()
    {
        return EmploymentType::all();
    }

    public function findEmployeeById($employee): ?Employee
    {
        return Employee::find($employee);
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
                Redis::del('all_employees');

                $this->employeeStoreLog->info('Employee record inserted into DB', [
                    'id'                 => $employee->id,
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

    public function updateEmployee($employee, array $data)
    {
        try {
            return DB::transaction(function () use ($employee, $data) {
                $employee->first_name         = $data['first_name'];
                $employee->last_name          = $data['last_name'];
                $employee->date_of_joining    = $data['date_of_joining'];
                $employee->employment_type_id = $data['employment_type_id'];
                $employee->department_id      = $data['department_id'];
                $employee->designation_id     = $data['designation_id'];
                $employee->updated_by         = auth()->id();

                $employee->save();
                Redis::del('all_employees');

                $this->employeeStoreLog->info('Employee record updated in DB', [
                    'id'                 => $employee->id,
                    'employee_name'      => $employee->first_name . ' ' . $employee->last_name,
                    'date_of_joining'    => $employee->date_of_joining,
                    'employment_type_id' => $employee->employment_type_id,
                    'department_id'      => $employee->department_id,
                    'designation_id'     => $employee->designation_id,
                ]);

                return $employee;
            });
        } catch (\Exception $e) {
            $this->employeeStoreLog->error('Error during Employee record update', [
                'employee_id'   => $employee->id ?? null,
                'error_message' => $e->getMessage(),
                'trace'         => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function updateEmployeeIdOnDevice($employee, $userid,$uid)
    {
        try {

            $employee->employee_id = $userid;
            $employee->employee_device_uid=$uid;
            $employee->save();
            Redis::del('all_employees');

            $this->employeeStoreLog->info('Employee record updated in DB', [
                'employee_id'          => $employee->id,
                'employee_employee_id' => $employee->employee_id,
            ]);
        } catch (\Exception $e) {
            $this->employeeStoreLog->error('Error during Employee record update', [
                'employee_id'   => $employee->id,
                'error_message' => $e->getMessage(),
                'trace'         => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function addEmployeePersonalInfo(Employee $employee, array $personalData)
    {
        try {
            $employee->phone_no          = $personalData['phone_no'];
            $employee->email             = $personalData['email'];
            $employee->date_of_birth     = $personalData['date_of_birth'];
            $employee->gender_id         = $personalData['gender_id'];
            $employee->religion_id       = $personalData['religion_id'];
            $employee->marital_status_id = $personalData['marital_status_id'];
            $employee->blood_group_id    = $personalData['blood_group_id'];
            $employee->national_id       = $personalData['national_id'];
            $employee->save();
            Redis::del('all_employees');

            $this->employeeStoreLog->info('Employee personal info saved', [
                'employee_id' => $employee->id,
            ]);
        } catch (\Exception $e) {
            $this->employeeStoreLog->error('Error saving employee personal info', [
                'employee_id'   => $employee->id,
                'error_message' => $e->getMessage(),
                'trace'         => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function addEmployeeAddress(Employee $employee, array $addressData)
    {
        try {
            $address = EmployeeAddress::firstOrNew([
                'employee_id' => $employee->id,
                'type'        => $addressData['type'],
            ]);

            $address->country     = $addressData['country'];
            $address->state       = $addressData['state'];
            $address->city        = $addressData['city'];
            $address->postal_code = $addressData['postal_code'];
            $address->address     = $addressData['address'];
            $address->save();

            $this->employeeStoreLog->info('Employee address saved', [
                'employee_id'  => $employee->id,
                'address_type' => $address->type,
            ]);
        } catch (\Exception $e) {
            $this->employeeStoreLog->error('Error saving employee address', [
                'employee_id'   => $employee->id,
                'error_message' => $e->getMessage(),
                'trace'         => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

public function deleteEmployee(Employee $employee)
{
    try {
        return DB::transaction(function () use ($employee) {

            $employee->delete();
            Redis::del('all_employees');

            $this->employeeStoreLog->info('Employee record soft-deleted', [
                'id'            => $employee->id,
                'employee_name' => $employee->first_name . ' ' . $employee->last_name,
                'deleted_at'    => now(),
            ]);

            return true;
        });
    } catch (\Exception $e) {
        $this->employeeStoreLog->error('Error during Employee deletion', [
            'id'            => $employee->id,
            'employee_name' => $employee->first_name . ' ' . $employee->last_name,
            'error_message' => $e->getMessage(),
            'trace'         => $e->getTraceAsString(),
        ]);

        throw $e;
    }
}

}
