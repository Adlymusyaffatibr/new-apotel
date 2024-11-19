<?php

use Illuminate\Support\Facades\Route;
// use : Import file : namespace\namaclass
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Controller;

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

// Route::get('/', function () {
//     return view('home');
// });

Route::get('/error-permission',function(){
    return view('error-permission');
})->name('error.permission');

Route::get('/', function () {
    return view('login');
});

//struktur routing laravel:
// route ::httpMethod('/nama-path', [NamaController::class, 'namaFunc'])->name ('identitas_route');
// http Method:
// 1. get -> mengambil data/menampilkan halaman
// 2.post -> menambahkan data baru ke db
// 3.patch/put -> mengubah data di db
// 4. delate -> menghapus data di db

//diakses sebelum login
Route::middleware(['isGuest'])->group(function() {
    Route::get('/', [AkunController::class, 'login'])->name('login');
    Route::post('/login', [AkunController::class, 'loginAuth'])->name('login.auth');
});

Route::middleware(['isLogin'])->group(function(){

    Route::get('/logout', [AkunController::class, 'logout'])->name('logout');
    
    Route::get('/home', [LandingPageController::class, 'index'])->name('home');
    
Route::middleware(['isAdmin'])->group(function(){
    Route::prefix('/medicine')->name('medicine.')->group(function() {
        Route::get('create', [MedicineController::class, 'create'])->name('create');
        Route::post('store', [MedicineController::class, 'store'])->name('store');
        Route::get ('/', [MedicineController::class, 'index']) ->name ('home');
        route::get('{id}', [MedicineController::class, 'edit'])->name ('edit');
        route::patch('{id}', [MedicineController::class, 'update'])->name ('update');
        route::delete('{id}', [MedicineController::class, 'destroy'])->name ('delete');
        route::get('/data/stock', [MedicineController::class, 'stock'])->name ('stock');
        route::get('/data/stock/{id}', [MedicineController::class, 'stockEdit'])->name ('stock.edit');
        route::patch('/data/stock/{id}', [MedicineController::class, 'stockUpdate'])->name ('stock.update');
    });
Route::prefix('/kelola')->name('kelola.')->group(function(){
    Route::get('/create', [AkunController::class, 'create'])->name('create');
    Route::post('/store', [AkunController::class, 'store'])->name('store');
    Route::get ('/', [AkunController::class, 'index']) ->name ('home');
    route::get('{id}', [AkunController::class, 'edit'])->name ('edit');
    route::patch('/{id}', [AkunController::class, 'update'])->name ('update');
    route::delete('/{id}', [AkunController::class, 'destroy'])->name ('delete');
    }); 
    Route::prefix('/order')->name('order.')->group(function(){
        Route::get('/data', [OrderController::class, 'data'])->name('data');
    });
});
});
Route::get('/pembelian',  [OrderController::class, 'index'])->name('pembelian');
Route::get('/create/pembelian',  [OrderController::class, 'create'])->name('tambah.pembelian');
Route::post('/store/pembelian',  [OrderController::class, 'store'])->name('store.pembelian');
Route::get('/order/kasir/show/{id}', [OrderController::class, 'show'])->name('order.kasir.show');
Route::get('/download/{id}', [OrderController::class, 'downloadPdf'])->name('download');
Route::post('/orders/{id}/update-date', [OrderController::class, 'updateDate']);
