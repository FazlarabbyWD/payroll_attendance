<?php

namespace App\Http\Controllers\DepartmentDesignation;

use App\Http\Requests\DesignationStoreRequest;
use App\Models\Department;
use App\Models\Designation;
use App\Http\Controllers\Controller;

class DesignationManageController extends Controller
{
    public function index(){

        $designations = Designation::with('department')->get();
        $departments  = Department::all();

        return view('designations.index', compact('designations','departments'));
    }

    public Function store(DesignationStoreRequest $request){

        dd($request->all());
    }
}
