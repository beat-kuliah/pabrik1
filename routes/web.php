<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
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

Route::controller(GudangController::class)->middleware('auth', 'role:ADMIN')->group(function () {
    Route::get('/gudang', 'index')->name('gudang');
    Route::post('/gudang', 'store');
    Route::get('/gudang/datatables', 'datatables');
    Route::get('/gudang/all', 'getAll');
});

Route::controller(VendorController::class)->middleware('auth', 'role:ADMIN')->group(function () {
    Route::get('/vendor', 'index')->name('vendor');
    Route::post('/vendor', 'store');
    Route::get('/vendor/datatables', 'datatables');
    Route::get('/vendor/all', 'getAll');
});

Route::controller(PenjualanController::class)->middleware('auth', 'role:ADMIN')->group(function () {
    Route::get('/penjualan', 'index')->name('penjualan');
    Route::post('/penjualan', 'store');
    Route::get('/penjualan/datatables', 'datatables');
    Route::get('/penjualan/generate-pdf/{id}', 'generatePDF');
});

Route::controller(BarangController::class)->middleware('auth', 'role:ADMIN')->group(function () {
    Route::get('/barang', 'index')->name('barang');
    Route::post('/barang', 'store');
    Route::get('/barang/datatables', 'datatables');
    Route::post('/barang/update-stok/{id}', 'updateStok');
    Route::get('/barang/find/{id}', 'findOne');
    Route::get('/barang/all', 'getAll');
});

Route::controller(ReportController::class)->middleware('auth', 'role:ADMIN')->group(function () {
    Route::get('/report', 'index');
    Route::get('/report/datatables', 'datatables');
    Route::get('/report/generate-pdf', 'generatePDF');
});
