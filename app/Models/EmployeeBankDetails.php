<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeBankDetails extends Model
{
    use SoftDeletes;

    protected $table = 'employee_bank_details';

    protected $fillable = [
        'employee_id',
        'bank_branch_id',
        'account_holder_name',
        'account_number',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Relationships
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function branch()
    {
        return $this->belongsTo(BankBranch::class, 'bank_branch_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
