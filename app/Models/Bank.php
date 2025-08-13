<?php
namespace App\Models;

use App\Models\BankBranch;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
    ];

    public function branches()
    {
        return $this->hasMany(BankBranch::class, 'bank_id');
    }

}
