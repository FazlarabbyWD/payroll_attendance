<?php
namespace App\Services;

use App\Exceptions\EmployeeCreationException;
use App\Http\Requests\EmployeeBasicInfoStoreRequet;
use App\Models\Employee;
use App\Repositories\DepartmentRepositoryInterface;
use App\Repositories\DeviceRepositoryInterface;
use App\Repositories\EmployeeRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Jmrashed\Zkteco\Lib\ZKTeco;

class EmployeeService implements EmployeeServiceInterface
{
    protected $employeeRepository;
    protected $employeeStoreLog;
    protected $departmentRepository;

    protected $deviceEmployeeAddRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository, DeviceRepositoryInterface $deviceEmployeeAddRepository, DepartmentRepositoryInterface $departmentRepository)
    {
        $this->employeeRepository          = $employeeRepository;
        $this->deviceEmployeeAddRepository = $deviceEmployeeAddRepository;
        $this->departmentRepository        = $departmentRepository;
        $this->employeeStoreLog            = Log::channel('employeeStoreLog');

    }
    public function getAllEmploymentTypes()
    {
        return $this->employeeRepository->getAllEmploymentTypes();
    }

    public function getAllEmployees()
    {
        return $this->employeeRepository->getAllEmployees();
    }

    public function findEmployeeById($employee): ?Employee
    {
        return $this->employeeRepository->findEmployeeById($employee);
    }

    public function getEmployeeStats()
    {
        $employeeStats = $this->employeeRepository->getEmployeeStats();
        $departments   = $this->departmentRepository->countDepartments();

        return [
            'employees'           => $employeeStats->total,
            'verifiedemployee'    => $employeeStats->verified,
            'pendingVerification' => $employeeStats->pending,
            'departments'         => $departments,
        ];
    }

    public function createAndAddToDevice($request): Employee
    {
        return DB::transaction(function () use ($request) {
            $employee = $this->createEmployee($request);
            // $result   = $this->addEmployeeToDevices($employee);

            // if (! $result) {
            //     throw new EmployeeCreationException('Failed to add employee to device');
            // }

            return $employee;
        });
    }

    public function createEmployee(EmployeeBasicInfoStoreRequet $request): Employee
    {
        $maxRetries = Config::get('employee.max_retries', 1);
        $retryDelay = Config::get('employee.initial_retry_delay_ms', 100);

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {

            $this->employeeStoreLog->info('Attempting Employee creation', [
                'attempt' => $attempt,
            ]);

            try {
                $employeeData = $request->validated();

                $employee = $this->employeeRepository->createEmployee($employeeData);

                $this->employeeStoreLog->info('Employee created', [
                    'attempt'            => $attempt,
                    'employee_id'        => $employee->id,
                    'employee_name'      => $employee->first_name . ' ' . $employee->last_name,
                    'date_of_joining'    => $employee->date_of_joining,
                    'employment_type_id' => $employee->employment_type_id,
                    'department_id'      => $employee->department_id,
                    'designation_id'     => $employee->designation_id,
                ]);

                return $employee;

            } catch (QueryException $e) {
                if ($this->isDeadlockOrConnectionException($e)) {
                    $this->employeeStoreLog->warning('Transient DB issue during Employee creation, retrying...', [
                        'attempt'       => $attempt,
                        'error_message' => $e->getMessage(),
                    ]);
                    usleep($retryDelay * 1000);
                    $retryDelay *= 2;
                    continue;
                }

                $this->employeeStoreLog->error('Query exception during Employee creation', [
                    'attempt'       => $attempt,
                    'error_message' => $e->getMessage(),
                ]);

                throw $e;
            } catch (\Exception $e) {
                $this->employeeStoreLog->error('Unexpected error during Employee creation', [
                    'attempt'       => $attempt,
                    'error_message' => $e->getMessage(),
                ]);

                throw $e;
            }
        }

        throw new EmployeeCreationException("Failed to create Employee after {$maxRetries} attempts.");
    }

    public function addEmployeeToDevices(Employee $employee): bool
    {
        $this->employeeStoreLog->info('Attempting to add employee to Devices', [
            'employee_id'   => $employee->id,
            'employee_name' => $employee->first_name . ' ' . $employee->last_name,
        ]);

        try {

            $deviceIps = $this->deviceEmployeeAddRepository->getAllActiveDeviceIps();

            if ($deviceIps->isEmpty()) {
                $this->employeeStoreLog->error('No active devices found.', [
                    'employee_id' => $employee->id,
                ]);
                return false;
            }

            $maxUid    = 0;
            $maxUserId = 0;

            foreach ($deviceIps as $deviceIp) {
                $zk = new ZKTeco($deviceIp);
                if (! $zk->connect()) {
                    $this->employeeStoreLog->warning("Failed to connect to device: $deviceIp", [
                        'employee_id' => $employee->id,
                        'device_ip'   => $deviceIp,
                    ]);
                    continue;
                }

                $users = $zk->getUser();
                if ($users === false) {
                    $this->employeeStoreLog->warning("Failed to get users from device: $deviceIp", [
                        'employee_id' => $employee->id,
                        'device_ip'   => $deviceIp,
                    ]);
                    $zk->disconnect();
                    continue;
                }

                foreach ($users as $user) {
                    if (! empty($user['uid']) && $user['uid'] > $maxUid) {
                        $maxUid = $user['uid'];
                    }
                    if (! empty($user['userid']) && $user['userid'] > $maxUserId) {
                        $maxUserId = $user['userid'];
                    }
                }
                $zk->disconnect();
            }

            $uid    = $maxUid + 1;
            $userid = $maxUserId + 1;
            $name   = $employee->first_name . ' ' . $employee->last_name;

            $anySuccess = false;

            foreach ($deviceIps as $deviceIp) {
                $zk = new ZKTeco($deviceIp);
                if (! $zk->connect()) {
                    $this->employeeStoreLog->warning("Failed to connect to device for adding user: $deviceIp", [
                        'employee_id' => $employee->id,
                        'device_ip'   => $deviceIp,
                    ]);
                    continue;
                }

                $result = $zk->setUser($uid, $userid, $name, null, 0, '0000000000');

                if ($result === false) {
                    $this->employeeStoreLog->warning("Failed to set user on device: $deviceIp", [
                        'employee_id' => $employee->id,
                        'device_ip'   => $deviceIp,
                        'uid'         => $uid,
                        'userid'      => $userid,
                    ]);
                    $zk->disconnect();
                    continue;
                }

                $zk->disconnect();
                $anySuccess = true;

                $this->employeeStoreLog->info("User added to device: $deviceIp", [
                    'employee_id' => $employee->id,
                    'uid'         => $uid,
                    'userid'      => $userid,
                    'device_ip'   => $deviceIp,
                ]);
            }

            if (! $anySuccess) {

                return false;
            }

            $this->employeeRepository->updateEmployeeIdOnDevice($employee, $userid);

            return true;
        } catch (\Exception $e) {
            $this->employeeStoreLog->error('Error adding employee to devices', [
                'employee_id'   => $employee->id,
                'error_message' => $e->getMessage(),
            ]);
            return false;
        }
    }


    public function addEmployeePersonalInfo(Employee $employee, array $personalData): Employee
    {
        try {
            $this->employeeRepository->addEmployeePersonalInfo($employee, $personalData);

            $this->employeeStoreLog->info('Employee personal info updated', [
                'employee_id' => $employee->id,
                'phone_no'    => $personalData['phone_no'],
                'email'       => $personalData['email'],
            ]);

            return $employee;
        } catch (\Exception $e) {
            $this->employeeStoreLog->error('Error updating employee personal info', [
                'employee_id'   => $employee->id,
                'error_message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function addEmployeeAddress(Employee $employee, array $addressData): Employee
    {
        try {
            if (! isset($addressData['type'])) {
                throw new \InvalidArgumentException("Address type is required.");
            }

            $this->employeeRepository->addEmployeeAddress($employee, $addressData);

            $this->employeeStoreLog->info('Employee address updated', [
                'employee_id'  => $employee->id,
                'address_type' => $addressData['type'],
            ]);

            return $employee;
        } catch (\Exception $e) {
            $this->employeeStoreLog->error('Error updating employee address', [
                'employee_id'   => $employee->id,
                'error_message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
    protected function isDeadlockOrConnectionException(\Exception $e): bool
    {
        $message    = $e->getMessage();
        $code       = $e->getCode();
        $isDeadlock = Str::contains($message, [
            'Deadlock found when trying to get lock',
            'Lock wait timeout exceeded',
            'SQLSTATE[HY000] [2002]',
            'Connection refused',
        ]) || in_array($code, ['40001', '40P01']);

        if ($isDeadlock) {
            $this->employeeStoreLog->warning('Deadlock or connection exception detected', [
                'error_message' => $message,
                'error_code'    => $code,
            ]);
        }

        return $isDeadlock;
    }
}
