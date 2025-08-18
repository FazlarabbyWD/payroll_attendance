<?php

namespace App\Services;

use App\Models\Leave;
use Illuminate\Database\Eloquent\Collection;

interface LeaveServiceInterface {

     public function getAllEmployee(): Collection;
    /**
     * Retrieve all leaves.
     *
     * @return Collection
     */
    public function getAllLeaves(): Collection;

    /**
     * Retrieve a leave by its ID.
     *
     * @param int $id
     * @return Leave|null
     */
    public function getLeaveById(int $id): ?Leave;

    /**
     * Create a new leave.
     *
     * @param array $data
     * @return Leave
     */
    public function createLeave(array $data): Leave;

    /**
     * Update an existing leave.
     *
     * @param int $id
     * @param array $data
     * @return Leave|null
     */
    public function updateLeave(int $id, array $data): ?Leave;

    /**
     * Delete a leave.
     *
     * @param int $id
     * @return bool
     */
    public function deleteLeave(int $id): bool;

    /**
     * Restore a soft-deleted leave.
     *
     * @param int $id
     * @return bool
     */
    public function restoreLeave(int $id): bool;

    /**
     * Permanently delete a leave.
     *
     * @param int $id
     * @return bool
     */
    public function forceDeleteLeave(int $id): bool;
}
