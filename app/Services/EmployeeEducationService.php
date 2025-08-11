<?php

namespace App\Services;

use App\Models\Employee;
use App\Repositories\EmployeeEducationRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeEducationService
{
    protected EmployeeEducationRepositoryInterface $educationRepository;

    public function __construct(EmployeeEducationRepositoryInterface $educationRepository)
    {
        $this->educationRepository = $educationRepository;
    }

    /**
     * Syncs (creates, updates, deletes) employee education records.
     *
     * @param Employee $employee The employee model.
     * @param array $educationData The validated education data from the request.
     * @param int|null $userId The ID of the authenticated user performing the action.
     * @return void
     */
    public function syncEducation(Employee $employee, array $educationData, ?int $userId): void
    {
        DB::transaction(function () use ($employee, $educationData, $userId) {
            $existingEducationIds = $this->educationRepository->getByEmployeeId($employee->id)->pluck('id')->toArray();
            $submittedEducationIds = [];

            foreach ($educationData as $eduItem) {
                $educationId = $eduItem['id'] ?? null;
                $certificateFile = $eduItem['certificate_file'] ?? null;
                $storedFilePath = null;

                // Prepare data for repository, excluding file object and 'id' for new records
                $dataToSave = [
                    'employee_id' => $employee->id, // Ensure employee_id is set for creation
                    'degree_name' => $eduItem['degree_name'],
                    'field_of_study' => $eduItem['field_of_study'] ?? null,
                    'institute_name' => $eduItem['institute_name'],
                    'board' => $eduItem['board'] ?? null,
                    'passing_year' => $eduItem['passing_year'],
                    'gpa' => $eduItem['gpa'] ?? null,
                ];

                // Handle file upload
                if ($certificateFile && $certificateFile->isValid()) {
                    $storedFilePath = $certificateFile->store('employee_certificates', 'public');
                    $dataToSave['certificate_file'] = $storedFilePath;
                }

                if ($educationId) {
                    // Update existing record
                    $currentEducation = $this->educationRepository->find($educationId);

                    if ($currentEducation) {
                        // If a new file was uploaded, and an old file existed, delete the old one
                        if ($storedFilePath && $currentEducation->certificate_file) {
                            Storage::disk('public')->delete($currentEducation->certificate_file);
                        }
                        // If no new file, but there was an old file, keep the old path
                        // This logic is handled by NOT overwriting certificate_file in $dataToSave if $storedFilePath is null
                        // and letting the update method handle only provided fields.
                        // Or, pass the existing certificate_file if no new one.
                        if (!$storedFilePath && $currentEducation->certificate_file) {
                             $dataToSave['certificate_file'] = $currentEducation->certificate_file;
                        } else if (!$storedFilePath) {
                             // If no new file and no existing file, set to null
                             $dataToSave['certificate_file'] = null;
                        }


                        $dataToSave['updated_by'] = $userId;
                        $updatedEducation = $this->educationRepository->update($educationId, $dataToSave);
                        if ($updatedEducation) {
                            $submittedEducationIds[] = $updatedEducation->id;
                        }
                    }
                } else {
                    // Create new record
                    $dataToSave['created_by'] = $userId;
                    $newEducation = $this->educationRepository->create($dataToSave);
                    $submittedEducationIds[] = $newEducation->id;
                }
            }

            // Soft delete education records that were not submitted in the current form
            $this->educationRepository->softDeleteMany($employee->id, $submittedEducationIds);
        });
    }
}
