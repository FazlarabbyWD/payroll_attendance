<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'company_employee');
    }

}
