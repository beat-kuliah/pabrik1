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

Route::controller(UserController::class)->middleware('auth')->group(function () {
    Route::get('/user', 'index')->name('user');
    Route::post('/user', 'store');
    Route::get('/user/show/{id}', 'show');
    Route::post('/user/update-role/{id}', 'update_role');
    Route::post('/user/update-password', 'update_password');
    Route::delete('/user/{id}', 'destroy');
    Route::get('/user/datatables', 'datatables');
    Route::get('/role', 'role');
});

Route::controller(GudangController::class)->middleware('auth')->group(function () {
    Route::get('/gudang', 'index')->name('gudang');
    Route::post('/gudang', 'store');
    Route::get('/gudang/show/{id}', 'show');
    Route::get('/gudang/destroy/{id}', 'destroy');
    Route::post('/gudang/update/{id}', 'update');
    Route::get('/gudang/datatables', 'datatables');
    Route::get('/gudang/all', 'getAll');
});

Route::controller(VendorController::class)->middleware('auth')->group(function () {
    Route::get('/vendor', 'index')->name('vendor');
    Route::get('/vendor/show/{id}', 'show');
    Route::get('/vendor/datatables', 'datatables');
    Route::get('/vendor/all', 'getAll');
    Route::get('/vendor/destroy/{id}', 'destroy');
    Route::post('/vendor', 'store');
    Route::post('/vendor/update/{id}', 'update');
});

Route::controller(PenjualanController::class)->middleware('auth')->group(function () {
    Route::get('/penjualan', 'index')->name('penjualan');
    Route::post('/penjualan', 'store');
    Route::post('/penjualan/update/{id}', 'update');
    Route::get('/penjualan/destroy/{id}', 'destroy');
    Route::get('/penjualan/show/{id}', 'show');
    Route::get('/penjualan/datatables', 'datatables');
    Route::get('/penjualan/generate-pdf/{id}', 'generatePDF');
});

Route::controller(BarangController::class)->middleware('auth')->group(function () {
    Route::get('/barang', 'index')->name('barang');
    Route::get('/barang/find/{id}', 'findOne');
    Route::get('/barang/all', 'getAll');
    Route::get('/barang/all/{gudang}', 'getAllGudang');
    Route::get('/barang/datatables', 'datatables');
    Route::get('/barang/destroy/{id}', 'destroy');
    Route::post('/barang/update-stok/{id}', 'updateStok');
    Route::post('/barang', 'store');
    Route::post('/barang/update/{id}', 'update');
});

Route::controller(ReportController::class)->middleware('auth')->group(function () {
    Route::get('/report-stok', 'indexStok');
    Route::get('/report-account', 'indexAccounting');
    Route::get('/report/stok/datatables', 'stokDatatables');
    Route::get('/report/stok/generate-pdf/{from}/{to}/{gudang}', 'stokGeneratePDF');
    Route::get('/report/penjualan/datatables', 'penjualanDatatables');
    Route::get('/report/penjualan/generate-pdf/{from}/{to}/{gudang}', 'penjualanGeneratePDF');
});
