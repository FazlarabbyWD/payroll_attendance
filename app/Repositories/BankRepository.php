<?php
namespace App\Repositories;

use App\Models\Bank;
use App\Models\EmployeeBankDetails;
use App\Repositories\BankRepositoryInterface;

class BankRepository implements BankRepositoryInterface
{

    public function getAll()
    {
        return Bank::with('branches')->get();
    }

    public function employeeBank(array $empBankData): EmployeeBankDetails
    {

        return EmployeeBankDetails::updateOrCreate(
            [
                'employee_id' => $empBankData['employee_id'] ?? null,
            ],
            [
                'bank_id'             => $empBankData['bank_id'],
                'bank_branch_id'           => $empBankData['bank_branch_id'],
                'account_holder_name' => $empBankData['account_holder_name'],
                'account_number'      => $empBankData['account_number'],
            ]
        );
    }

}
