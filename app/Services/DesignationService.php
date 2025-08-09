<?php
namespace App\Services;

use App\Exceptions\DesignationCreationException;
use App\Exceptions\DesignationNotFoundException;
use App\Http\Requests\DesignationStoreRequest;
use App\Http\Requests\DesignationUpdateRequest;
use App\Models\Designation;
use App\Repositories\DesignationRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DesignationService implements DesignationServiceInterface
{

    protected $designationRepository;
    protected $designationCrudLog;

    public function __construct(DesignationRepositoryInterface $designationRepository)
    {
        $this->designationRepository = $designationRepository;
        $this->designationCrudLog    = Log::channel('designationStoreLog');
    }

    public function getAllDesignations()
    {
        return $this->designationRepository->getDesignations();
    }

    public function createDesignation(DesignationStoreRequest $request): Designation
    {
        $maxRetries = Config::get('device.max_retries', 3);
        $retryDelay = Config::get('device.initial_retry_delay_ms', 100);

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {

            $this->designationCrudLog->info('Attempting Designation creation', [
                'attempt' => $attempt,

            ]);

            try {
                $designationData = $request->validated();

                $designation = $this->designationRepository->create($designationData);

                $this->designationCrudLog->info('Designation created', [
                    'attempt'        => $attempt,
                    'designation_id' => $designation->id,
                    'name'           => $designation->name,
                ]);

                return $designation;

            } catch (QueryException $e) {
                if ($this->isDeadlockOrConnectionException($e)) {
                    $this->designationCrudLog->warning('Transient DB issue during user creation, retrying...', [
                        'attempt'       => $attempt,

                        'error_message' => $e->getMessage(),
                    ]);
                    usleep($retryDelay * 1000);
                    $retryDelay *= 2;
                    continue;
                }

                $this->designationCrudLog->error('Query exception during Designation creation', [
                    'attempt'       => $attempt,
                    'error_message' => $e->getMessage(),
                ]);

                throw $e;
            } catch (\Exception $e) {
                $this->designationCrudLog->error('Unexpected error during Designation creation', [
                    'attempt'       => $attempt,
                    'error_message' => $e->getMessage(),
                ]);
                throw $e;
            }
        }
        throw new DesignationCreationException("Failed to create Designation after {$maxRetries} attempts.");
    }

    public function findDesignation(string $id): ?Designation
    {
        try {
            $designation = $this->designationRepository->find($id);

            if (! $designation) {
                $this->designationCrudLog->warning("designation not found", ['designation_id' => $id]);
                throw new DesignationNotFoundException("designation with ID {$id} not found.");
            }

            return $designation;
        } catch (\Exception $e) {
            $this->designationCrudLog->error("Error finding designation", [
                'designation_id' => $id,
                'error_message'  => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function updateDesignation(DesignationUpdateRequest $request, string $id): Designation
    {
        $designation = $this->findDesignation($id);

        try {
            $designationData = $request->validated();
            $designation     = $this->designationRepository->update($designation, $designationData);

            return $designation;
        } catch (\Exception $e) {
            $this->designationCrudLog->error('Failed to update designation', [
                'designation_id' => $id,
                'error_message'  => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function deleteDesignation(string $id): void
    {
        $designation = $this->findDesignation($id); // Reuse findDesignation for validation

        try {
            $this->designationRepository->delete($designation);
        } catch (\Exception $e) {
            $this->designationCrudLog->error('Failed to delete designation', [
                'designation_id' => $id,
                'error_message'  => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function getDesignationByDept(int $departmentId): array
    {
        try {
            return $this->designationRepository->getDesignationByDept($departmentId);
        } catch (\Exception $e) {
            $this->designationCrudLog->error('Failed to get designations by department', [
                'department_id' => $departmentId,
                'error_message' => $e->getMessage(),
            ]);
            throw $e;
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
