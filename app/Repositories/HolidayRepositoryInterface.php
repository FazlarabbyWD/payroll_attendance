<?php

namespace App\Repositories;

use App\Models\Holiday;

interface HolidayRepositoryInterface
{
    public function getAll();
    public function find(int $id): ?Holiday;
    public function create(array $data): Holiday;
    public function update(Holiday $holiday, array $data): Holiday;
    public function delete(Holiday $holiday): bool;
    // public function forceDelete(int $id): bool;
    // public function restore(int $id): bool;
}
