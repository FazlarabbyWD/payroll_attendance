<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\DepartmentDesignation\DepartmentManageController;
use App\Http\Controllers\DepartmentDesignation\DesignationManageController;
use App\Http\Controllers\Device\DeviceManageController;
use App\Http\Controllers\Employee\EmployeeManageController;
use App\Http\Controllers\Test\TestController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/test', [TestController::class, 'index'])->name('test');

Route::get('/', [AuthenticationController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthenticationController::class, 'loginSubmit'])->name('login.submit');
Route::get('/logout', [AuthenticationController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('devices', DeviceManageController::class);
    Route::resource('departments', DepartmentManageController::class);
    Route::resource('designations', DesignationManageController::class);
    Route::resource('employees', EmployeeManageController::class);

    Route::get('/get-designations-by-department', [EmployeeManageController::class, 'getDesignationsByDepartment'])->name('get.designations.by.department');

    Route::get('/employee/{employee}/personal-info', [EmployeeManageController::class, 'showPersonalInfoForm'])->name('employees.personal-info');

    Route::post('employee/{employee}/update/personal-info', [EmployeeManageController::class, 'storePersonalAddressInfo'])->name('employees.personal-info.store');

    Route::get('/employees/{employee}/address', [EmployeeManageController::class, 'getAddress'])->name('employees.address');
});
