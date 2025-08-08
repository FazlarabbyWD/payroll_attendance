<?php
namespace App\Repositories;

use App\Models\EmploymentType;
use App\Repositories\EmployeeRepositoryInterface;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    // Define methods for employee repository here

      public function getAllEmploymentTypes()
    {
        return EmploymentType::all();
    }



}
