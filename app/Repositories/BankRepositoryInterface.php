<?php
namespace App\Repositories;

use App\Models\EmployeeBankDetails;

interface BankRepositoryInterface
{
    public function getAll();

    public function employeeBank(array $empBankData): EmployeeBankDetails;
}
