<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdukController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;
use PharIo\Manifest\AuthorCollectionIterator;

Route::get('/', function () {
    return view('Welcome');
});

// //Route::get('contoh', function () {
//     //return view('contoh');
// //});
// Route::get('/home', [HomeController::class, 'ViewHome']);
// Route::get('/produk', [ProdukController::class, 'ViewProduk']);
// Route::get('/produk/add', [ProdukController::class, 'ViewAddProduk']);
// Route::post('/produk/add', [ProdukController::class, 'CreateProduk']);

// Route::delete('produk/delete/{kode_produk}', [ProdukController::class, 'DeleteProduk']);
// Route::get('/produk/edit/{kode_produk}',[ProdukController::class, 'ViewEditProduk']);
// Route::put('/produk/edit/{kode_produk}',[ProdukController::class, 'UpdateProduk']);

// Route::get('/laporan', [ProdukController::class, 'ViewLaporan']);
// Route::get('/report', [ProdukController::class, 'print']);
// //Route::get('produk',function(){
//     //return view('produk');
// //});
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout']);

//route untuk user
Route::middleware(['auth', 'user-access:user'])->prefix('user')->group(function () {
Route::get('/home', [HomeController::class, 'ViewHome']);
Route::get('/produk', [ProdukController::class, 'ViewProduk']);
Route::get('/produk/add', [ProdukController::class, 'ViewAddProduk']);
Route::post('/produk/add', [ProdukController::class, 'CreateProduk']);

Route::delete('/produk/delete/{kode_produk}', [ProdukController::class, 'DeleteProduk']);
Route::get('/produk/edit/{kode_produk}', [ProdukController::class, 'ViewEditProduk']);
Route::put('/produk/edit/{kode_produk}', [ProdukController::class, 'UpdateProduk']);

Route::get('/report', [ProdukController::class, 'print']);
Route::get('/laporan', [ProdukController::class, 'ViewLaporan']);
});

//route untuk admin
Route::middleware(['auth', 'user-access:admin'])->prefix('admin')->group(function () {
Route::get('/home', [HomeController::class, 'ViewHome']);
Route::get('/produk', [ProdukController::class, 'ViewProduk']);
Route::get('/produk/add', [ProdukController::class, 'ViewAddProduk']);
Route::post('/produk/add', [ProdukController::class, 'CreateProduk']);

Route::delete('/produk/delete/{kode_produk}', [ProdukController::class, 'DeleteProduk']);
Route::get('/produk/edit/{kode_produk}', [ProdukController::class, 'ViewEditProduk']);
Route::put('/produk/edit/{kode_produk}', [ProdukController::class, 'UpdateProduk']);

Route::get('/report', [ProdukController::class, 'print']);
Route::get('/laporan', [ProdukController::class, 'ViewLaporan']);
});
