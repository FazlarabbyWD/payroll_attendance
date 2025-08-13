<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceSyncLog extends Model
{
    // Mass assignable fields
    protected $fillable = [
        'device_id',
        'type',
        'last_sync',
    ];

    // Casts for attributes
    protected $casts = [
        'last_sync' => 'datetime',
    ];

    /**
     * Relationship: DeviceSyncLog belongs to a Device
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
