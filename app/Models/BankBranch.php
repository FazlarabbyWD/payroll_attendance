<?php
namespace App\Models;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class BankBranch extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'bank_id',
        'branch_name',
        'routing_no',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

}
