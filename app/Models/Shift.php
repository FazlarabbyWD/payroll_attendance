<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'grace_period',
        'break_minutes',
        'is_night_shift',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_night_shift' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // A shift can be assigned to many employees
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_shift_assignments')
            ->withPivot(['start_date', 'end_date'])
            ->withTimestamps();
    }
}
