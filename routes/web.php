<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Dashboard\DashboardController;

Route::get('/', [AuthenticationController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthenticationController::class, 'loginSubmit'])->name('login.submit');
Route::get('/logout', [AuthenticationController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function () {

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

Route::resource('users', UserController::class);


});
