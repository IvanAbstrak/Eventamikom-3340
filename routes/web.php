<?php

use Illuminate\Support\Facades\Route;
use App\http\Controllers\Homecontroller;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\DashboardController;
use Symfony\Contracts\EventDispatcher\Event;

// Rute User Area
Route::get('/', [Homecontroller::class, 'index'])->name('home');
Route::get('/event/1', [Eventcontroller::class, 'show'])->name('events.show');
Route::get('/checkout', [Eventcontroller::class, 'checkout'])->name('checkout');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

// Rute Admin Area
Route::group(['prefix'=>'admin','as' => 'admin.'], function() {
    Route::get('/', [DashboardController::class,'index'])->name('dashbooard');
    Route::get('/events', [EventController::class,'indexAdmin'])->name('events.index');
});
// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/tentang', function () {
//     return '<h1>Ini adalah Halaman Tentang Aplikasi Event Hub</h1>';
// });

// Route::get('/kontak', function () {
//     return view('contact');
// });

// Route::get('/profil', function () {
//     return view('profil');
// });

// Route::get('/katalog', function () {
//     return view('katalog');
// });

// Route::get('/bantuan', function () {
//     return view('bantuan');
// });


