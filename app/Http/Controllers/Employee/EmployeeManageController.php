<?php
namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\BloodGroup;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Gender;
use App\Models\MaritalStatus;
use App\Models\Religion;

class EmployeeManageController extends Controller
{
    public function index()
    {

        return view('employees.index');
    }

    public function create()
    {
        $departments     = Department::all();
        $designations    = Designation::all();

        return view('employees.create', compact('departments', 'designations'));
    }
}
