<?php

namespace App\Services;

use App\Repositories\BankRepositoryInterface;

class EmployeeBankService
{
    protected $bankRepository;

    public function __construct(BankRepositoryInterface $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }

    public function storeEmployeeBank(array $empBankData)
    {
        return $this->bankRepository->employeeBank($empBankData);
    }
}
