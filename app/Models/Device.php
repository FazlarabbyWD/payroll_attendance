<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{

    use SoftDeletes;
    protected $fillable = [
        'device_name',
        'location',
        'ip_address',
        'port',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


      public function syncLogs()
    {
        return $this->hasMany(DeviceSyncLog::class, 'device_id');
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
