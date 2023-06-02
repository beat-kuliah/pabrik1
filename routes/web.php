<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::controller(UserController::class)->middleware('auth', 'role:ADMIN')->group(function () {
    Route::get('/user', 'index')->name('user');
    Route::get('/user/{id}/update', 'edit');
    Route::delete('/user/{id}', 'destroy');
    Route::get('/user/datatables', 'datatables');
    Route::get('/role', 'role');
});
