<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MidtransWebhookController;

// Rute Publik / Pengunjung
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', function () { return '<h1>Ini adalah Halaman Tentang Aplikasi Event Hub</h1>'; });
Route::get('/kontak', function () { return view('contact'); });
Route::get('/profil', function () { return view('profil'); });
Route::get('/katalog', function () { return view('katalog'); });
Route::get('/bantuan', function () { return view('bantuan'); });

// Menggunakan {event} untuk Route Model Binding
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Rute Checkout Publik
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');

// Rute Halaman Pembayaran (DIPINDAH KE SINI AGAR BISA DIAKSES PUBLIK)
Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
// Tambahkan tanda tanya (?) agar {order_id} bersifat opsional
Route::get('/success/{order_id?}', [CheckoutController::class, 'success'])->name('checkout.success');

Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

// =============== LETAKKAN WEBHOOK MIDTRANS DI SINI ===============
// Berada di luar grup admin agar bisa diakses bebas oleh server Midtrans
Route::post('/midtrans/callback', [MidtransWebhookController::class, 'handle']);
// =================================================================

// Rute redirect login bawaan Laravel agar mengarah ke admin login
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Rute Admin Area
Route::prefix('admin')->name('admin.')->group(function() {

    // Rute Login bebas akses (di luar middleware)
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Mengamankan Route Administrasi di balik tembok (Middleware)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class,'index'])->name('dashboard');
        Route::resource('events', EventAdminController::class);

        // Laporan Transaksi
        Route::get('transactions', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');

        // Route untuk kategori dan partner
        Route::resource('categories', CategoryController::class);
        Route::resource('partners', PartnerController::class);
    });
});
