<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    use SoftDeletes;

    protected $table = 'holidays';

    // Fillable fields for mass assignment
    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'is_recurring',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Dates to be cast automatically
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_recurring' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // Relations to users who created, updated, deleted the record
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
