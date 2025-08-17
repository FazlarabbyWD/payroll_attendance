<?php

namespace App\Repositories;

use App\Models\Holiday;

class HolidayRepository implements HolidayRepositoryInterface
{
    public function getAll()
    {
        return Holiday::all();
    }

    public function find(int $id): ?Holiday
    {
        return Holiday::find($id);
    }

    public function create(array $data): Holiday
    {
        return Holiday::create($data);
    }

    public function update(Holiday $holiday, array $data): Holiday
    {
        $holiday->update($data);
        return $holiday;
    }

    public function delete(Holiday $holiday): bool
    {
        return $holiday->delete();
    }

//     public function forceDelete(int $id): bool
//     {
//         $holiday = Holiday::withTrashed()->findOrFail($id);
//         return $holiday->forceDelete();
//     }

//     public function restore(int $id): bool
//     {
//         $holiday = Holiday::withTrashed()->findOrFail($id);
//         return $holiday->restore();
//     }
}
