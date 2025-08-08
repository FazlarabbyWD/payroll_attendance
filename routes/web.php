<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Test\TestController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Device\DeviceManageController;
use App\Http\Controllers\Employee\EmployeeManageController;
use App\Http\Controllers\DepartmentDesignation\DepartmentManageController;
use App\Http\Controllers\DepartmentDesignation\DesignationManageController;

Route::get('/test',[TestController::class, 'index'])->name('test');

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


});
