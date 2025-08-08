<?php
namespace App\Services;

use App\Repositories\EmployeeRepositoryInterface;

class EmployeeService implements EmployeeServiceInterface
{
    protected $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;

    }
    public function getAllEmploymentTypes()
    {
        return $this->employeeRepository->getAllEmploymentTypes();
    }
}
