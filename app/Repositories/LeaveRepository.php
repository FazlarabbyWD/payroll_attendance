<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Database\Eloquent\Collection;

class LeaveRepository implements LeaveRepositoryInterface {

     public function getAllEmployee():Collection{
         return Employee::all();
     }
    public function getAll(): Collection {
        return Leave::all();
    }

    public function getById(int $id): ?Leave {
        return Leave::find($id);
    }

    public function create(array $data): Leave {
        return Leave::create($data);
    }

    public function update(int $id, array $data): ?Leave {
        $leave = Leave::find($id);
        if ($leave) {
            $leave->update($data);
            return $leave;
        }
        return null;
    }

    public function delete(int $id): bool {
        $leave = Leave::find($id);
        if ($leave) {
            return $leave->delete(); // Soft delete
        }
        return false;
    }

    public function restore(int $id): bool {
        $leave = Leave::withTrashed()->find($id);
        if ($leave && $leave->trashed()) {
            return $leave->restore();
        }
        return false;
    }

    public function forceDelete(int $id): bool {
        $leave = Leave::withTrashed()->find($id);
        if ($leave) {
            return $leave->forceDelete();
        }
        return false;
    }
}
