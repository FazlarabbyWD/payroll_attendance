<?php
namespace App\Services;

use App\Models\Employee;
use Illuminate\Support\Str;
use Jmrashed\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\QueryException;
use App\Exceptions\EmployeeCreationException;
use App\Repositories\DeviceRepositoryInterface;
use App\Repositories\EmployeeRepositoryInterface;
use App\Http\Requests\EmployeeBasicInfoStoreRequet;
use League\CommonMark\Parser\Inline\NewlineParser;

class EmployeeService implements EmployeeServiceInterface
{
    protected $employeeRepository;
    protected $employeeStoreLog;

    protected $deviceEmployeeAddRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository, DeviceRepositoryInterface $deviceEmployeeAddRepository)
    {
        $this->employeeRepository          = $employeeRepository;
        $this->deviceEmployeeAddRepository = $deviceEmployeeAddRepository;
        $this->employeeStoreLog            = Log::channel('employeeStoreLog');

    }
    public function getAllEmploymentTypes()
    {
        return $this->employeeRepository->getAllEmploymentTypes();
    }

     public function createAndAddToDevice($request):Employee
    {
        return DB::transaction(function () use ($request) {
            $employee = $this->createEmployee($request);
            $result = $this->addEmployeeToDevices($employee);

            if (! $result) {
                throw new EmployeeCreationException ('Failed to add employee to device');
            }

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
        $this->employeeStoreLog->info('Attempting to add employee to Device', [
            'employee_id'   => $employee->id,
            'employee_name' => $employee->first_name . ' ' . $employee->last_name,
        ]);

        try {

            $deviceIp = $this->deviceEmployeeAddRepository->getActiveDeviceIp();

            if (! $deviceIp) {
                $this->employeeStoreLog->error('No active device found.', [
                    'employee_id' => $employee->id,
                ]);
                return false;
            }

            $zk = new ZKTeco($deviceIp);
            $connected = $zk->connect();

            if (! $connected) {
                $this->employeeStoreLog->error('Failed to connect to ZKTeco device', [
                    'employee_id' => $employee->id,
                    'device_ip'   => $deviceIp,
                ]);
                return false;
            }

            $users = $zk->getUser();

            if ($users === false) {
                $this->employeeStoreLog->error('Failed to get users from device', [
                    'employee_id' => $employee->id,
                    'device_ip'   => $deviceIp,
                ]);
                $zk->disconnect();
                return false;
            }

            $maxUid = $maxUserId = 0;
            foreach ($users as $user) {
                if (! empty($user['uid']) && $user['uid'] > $maxUid) {
                    $maxUid = $user['uid'];
                }
                if (! empty($user['userid']) && $user['userid'] > $maxUserId) {
                    $maxUserId = $user['userid'];
                }
            }

            $uid    = $maxUid + 1;
            $userid = $maxUserId + 1;
            $name   = $employee->first_name . ' ' . $employee->last_name;

            $result = $zk->setUser($uid, $userid, $name, null, 0, '0000000000');
            if ($result === false) {
                $this->employeeStoreLog->error('Failed to set user in ZKTeco device', [
                    'employee_id' => $employee->id,
                    'device_ip'   => $deviceIp,
                    'uid'         => $uid,
                    'userid'      => $userid,
                ]);
                $zk->disconnect();
                return false;
            }

            $this->employeeRepository->updateEmployeeIdOnDevice($employee, $userid);
            $zk->disconnect();


            $this->employeeStoreLog->info('Employee added to device successfully', [
                'employee_id' => $employee->id,
                'uid'         => $uid,
                'userid'      => $userid,
                'device_ip'   => $deviceIp,
            ]);

            return true;
        } catch (\Exception $e) {
            $this->employeeStoreLog->error('Error adding employee to device', [
                'employee_id'   => $employee->id,
                'error_message' => $e->getMessage(),
            ]);
            return false;
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
