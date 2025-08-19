<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'total_minutes',
        'late_by',
        'early_leave_by',
        'overtime_minutes',
        'status',
        'is_manual',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime:H:i',
        'check_out' => 'datetime:H:i',
        'late_by' => 'datetime:H:i',
        'early_leave_by' => 'datetime:H:i',
        'is_manual' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Each attendance belongs to one employee
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id','employee_id');
    }

    // Useful if you want to link back to raw logs of that day
    public function logs()
    {
        return $this->hasMany(AttendanceLog::class, 'employee_id', 'employee_id')
            ->whereDate('timestamp', $this->date);
    }
}
