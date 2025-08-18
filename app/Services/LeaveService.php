<?php

namespace App\Services;

use App\Models\Leave;
use App\Repositories\LeaveRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LeaveService implements LeaveServiceInterface {

    protected $leaveRepository;

    public function __construct(LeaveRepositoryInterface $leaveRepository) {
        $this->leaveRepository = $leaveRepository;
    }

     public function getAllEmployee(): Collection {
        return $this->leaveRepository->getAllEmployee();
     }
    public function getAllLeaves(): Collection {
        return $this->leaveRepository->getAll();
    }

    public function getLeaveById(int $id): ?Leave {
        return $this->leaveRepository->getById($id);
    }

    public function createLeave(array $data): Leave {
        return $this->leaveRepository->create($data);
    }

    public function updateLeave(int $id, array $data): ?Leave {
        return $this->leaveRepository->update($id, $data);
    }

    public function deleteLeave(int $id): bool {
        return $this->leaveRepository->delete($id);
    }

    public function restoreLeave(int $id): bool {
        return $this->leaveRepository->restore($id);
    }

    public function forceDeleteLeave(int $id): bool {
        return $this->leaveRepository->forceDelete($id);
    }
}
