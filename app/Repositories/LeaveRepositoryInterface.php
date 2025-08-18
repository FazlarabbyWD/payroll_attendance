<?php
namespace App\Repositories;

use App\Models\Leave;
use Illuminate\Database\Eloquent\Collection;

interface LeaveRepositoryInterface
{


  
    /**
     * Retrieve all leaves.
     *
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Retrieve a leave by its ID.
     *
     * @param int $id
     * @return Leave|null
     */
    public function getById(int $id): ?Leave;

    /**
     * Create a new leave.
     *
     * @param array $data
     * @return Leave
     */
    public function create(array $data): Leave;

    /**
     * Update an existing leave.
     *
     * @param int $id
     * @param array $data
     * @return Leave|null
     */
    public function update(int $id, array $data): ?Leave;

    /**
     * Delete a leave (soft delete).
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Restore a soft-deleted leave.
     *
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool;

    /**
     * Permanently delete a leave.
     *
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id): bool;
}
