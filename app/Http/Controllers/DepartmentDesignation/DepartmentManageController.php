<?php

namespace App\Http\Controllers\DepartmentDesignation;

use App\Http\Requests\DepartmentStoreRequest;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentManageController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('departments.index', compact('departments'));

    }

    public function store(DepartmentStoreRequest $request){

        dd($request->all());

    }
}
