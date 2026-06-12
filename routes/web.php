<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;

// Rute Publik / Pengunjung (Soal 4 akan dieksekusi di HomeController)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', function () { return '<h1>Ini adalah Halaman Tentang Aplikasi Event Hub</h1>'; });
Route::get('/kontak', function () { return view('contact'); });
Route::get('/profil', function () { return view('profil'); });
Route::get('/katalog', function () { return view('katalog'); });
Route::get('/bantuan', function () { return view('bantuan'); });

Route::get('/event/{id}', [EventController::class, 'show'])->name('events.show');
Route::get('/checkout', [EventController::class, 'checkout'])->name('checkout');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

// Rute redirect login bawaan Laravel agar mengarah ke admin login
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Rute Admin Area (Soal 1, 2, dan 3)
Route::prefix('admin')->name('admin.')->group(function() {

    // Rute Login bebas akses (di luar middleware)
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Mengamankan Route Administrasi di balik tembok (Middleware)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class,'index'])->name('dashboard');
        Route::resource('events', EventAdminController::class);

        // Tambahan Route untuk UTS (Sekarang terlindungi oleh Middleware)
        Route::resource('categories', CategoryController::class);
        Route::resource('partners', PartnerController::class);
    });
});
