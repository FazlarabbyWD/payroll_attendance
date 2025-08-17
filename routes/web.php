<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Test\TestController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Device\DeviceManageController;
use App\Http\Controllers\Device\DeviceDataSyncController;
use App\Http\Controllers\Employee\EmployeeManageController;
use App\Http\Controllers\Attendance\AttendanceLogController;
use App\Http\Controllers\DepartmentDesignation\DepartmentManageController;
use App\Http\Controllers\DepartmentDesignation\DesignationManageController;

Route::get('/test', [TestController::class, 'index'])->name('test');
Route::get('/sync', [TestController::class, 'syncAttendance'])->name('sync');

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
    Route::post('/employees/{employee}/address', [EmployeeManageController::class, 'storeAddress'])->name('employees.address.store');

    Route::get('/employees/{employee}/education', [EmployeeManageController::class, 'getEducation'])->name('employees.education');

    Route::post('/employees/{employee}/education', [EmployeeManageController::class, 'storeEducation'])->name('employees.education.store');

    Route::get('/employees/{employee}/bankinfo', [EmployeeManageController::class, 'getBank'])->name('employees.bankinfo');
    Route::post('/employees/{employee}/bankinfo', [EmployeeManageController::class, 'storeBank'])->name('employees.bankinfo.store');

    Route::get('/device/data/sync', [DeviceDataSyncController::class, 'index'])->name('devices.data.sync');

    Route::post('/devices/{device}/sync', [DeviceDataSyncController::class, 'sync'])->name('devices.sync');

    Route::get('/attendance.log', [AttendanceLogController::class, 'index'])->name('attendance.log');

    Route::post('/attendance/sync', function () {
        Artisan::call('app:sync-attendance');
        return back()->with('success', 'Attendance Sync started!');
    })->name('attendance.sync');

    Route::post('/attendance/process', function () {
        Artisan::call('app:process-attendance');
        return back()->with('success', 'Attendance Processing started!');
    })->name('attendance.process');

});
