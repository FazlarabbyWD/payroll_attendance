<?php
namespace App\Services;

use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\QueryException;
use App\Exceptions\DepartmentUpdateException;
use App\Http\Requests\DepartmentStoreRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use App\Exceptions\DepartmentCreationException;
use App\Exceptions\DepartmentDeletionException;
use App\Exceptions\DepartmentNotFoundException;
use App\Repositories\DepartmentRepositoryInterface;



class DepartmentService implements DepartmentServiceInterface{

      protected $departmentRepository;
    protected $departmentCrudLog;

    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
        $this->departmentCrudLog    = Log::channel('departmentStoreLog');
    }

    public function getAllDepartments()
    {
        return $this->departmentRepository->getAll();
    }

     public function createDepartment(DepartmentStoreRequest $request): Department
    {
        $maxRetries = Config::get('device.max_retries', 3);
        $retryDelay = Config::get('device.initial_retry_delay_ms', 100);

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {

            $this->departmentCrudLog->info('Attempting Department creation', [
                'attempt' => $attempt,

            ]);

            try {
                $departmentData = $request->validated();

                $department = $this->departmentRepository->create($departmentData);

                $this->departmentCrudLog->info('Department created', [
                    'attempt'     => $attempt,
                    'department_id'   => $department->id,
                    'name' => $department->name,
                ]);

                return $department;

            } catch (QueryException $e) {
                if ($this->isDeadlockOrConnectionException($e)) {
                    $this->departmentCrudLog->warning('Transient DB issue during user creation, retrying...', [
                        'attempt'       => $attempt,

                        'error_message' => $e->getMessage(),
                    ]);
                    usleep($retryDelay * 1000);
                    $retryDelay *= 2;
                    continue;
                }

                $this->departmentCrudLog->error('Query exception during user creation', [
                    'attempt'       => $attempt,

                    'error_message' => $e->getMessage(),
                ]);

                throw $e;
            } catch (\Exception $e) {
                $this->departmentCrudLog->error('Unexpected error during user creation', [
                    'attempt'       => $attempt,

                    'error_message' => $e->getMessage(),
                ]);

                throw $e;
            }
        }

        throw new DepartmentCreationException("Failed to create Device after {$maxRetries} attempts.");
    }


    public function findDepartment(string $id): ?Department
    {
        try {
            $department = $this->departmentRepository->find($id);

            if (! $department) {
                $this->departmentCrudLog->warning("department not found", ['department_id' => $id]);
                throw new DepartmentNotFoundException("department with ID {$id} not found.");
            }

            return $department;
        } catch (\Exception $e) {
            $this->departmentCrudLog->error("Error finding department", [
                'department_id'     => $id,
                'error_message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }



      public function updateDepartment(DepartmentUpdateRequest $request, string $id): Department
    {
        try {
            $department = $this->findDepartment($id); // Use findDepartment to validate existence

            $departmentData = $request->validated();

            $department = $this->departmentRepository->update($department, $departmentData); // Pass Department object to update

            $this->departmentCrudLog->info('Department updated successfully', [
                'department_id' => $department->id,
                'name' => $department->name,
            ]);

            return $department;
        } catch (DepartmentNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->departmentCrudLog->error("Failed to update department", [
                'department_id'   => $id,
                'error_message' => $e->getMessage(),
            ]);
            throw new DepartmentUpdateException("Failed to update department: " . $e->getMessage(), 0, $e);
        }
    }

    public function deleteDepartment(string $id): void
    {
        try {
            $department = $this->findDepartment($id);

            $this->departmentRepository->delete($department);

            $this->departmentCrudLog->info("Department deleted successfully", ['department_id' => $id]);
        } catch (DepartmentNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->departmentCrudLog->error("Failed to delete department", [
                'department_id'   => $id,
                'error_message' => $e->getMessage(),
            ]);
            throw new DepartmentDeletionException("Failed to delete department: " . $e->getMessage(), 0, $e);
        }
    }


     protected function isDeadlockOrConnectionException(\Exception $e): bool
    {
        $message = $e->getMessage();
        $code    = $e->getCode();

        return Str::contains($message, [
            'Deadlock found when trying to get lock',
            'Lock wait timeout exceeded',
            'SQLSTATE[HY000] [2002]',
            'Connection refused',
        ]) || in_array($code, ['40001', '40P01']);
    }

}
