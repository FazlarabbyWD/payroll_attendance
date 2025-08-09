<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeAddress extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'type',
        'country',
        'state',
        'city',
        'postal_code',
        'address',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'type' => 'string', // enum values stored as string
    ];

    /* Relationships */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
