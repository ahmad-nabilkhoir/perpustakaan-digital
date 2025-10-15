<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PeminjamanController; // User
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\PeminjamanController as AdminPeminjamanController; // Admin kelola peminjaman
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// ==========================
// RUTE UNTUK USER UMUM
// ==========================

// Halaman utama (katalog buku)
Route::get('/', [BookController::class, 'index'])->name('home');

// Halaman detail buku
Route::get('/book/{book}', [BookController::class, 'show'])->name('book.show');

// Form pengajuan peminjaman (user ajukan pinjam)
Route::get('/book/{book}/peminjaman', [PeminjamanController::class, 'create'])->name('peminjaman.create');

// Proses pengajuan peminjaman
Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');


// ==========================
// AUTH (LOGIN / LOGOUT)
// ==========================

// Form login
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

// Proses login
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

// Proses logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');


// ==========================
// RUTE UNTUK ADMIN
// ==========================
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // CRUD Buku
    Route::resource('books', AdminBookController::class)->except(['show']);

    // CRUD Peminjaman + Aksi Khusus
    Route::resource('peminjaman', AdminPeminjamanController::class)->except(['show']);
    Route::put('/peminjaman/{peminjaman}/approve', [AdminPeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::put('/peminjaman/{peminjaman}/reject', [AdminPeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::put('/peminjaman/{peminjaman}/return', [AdminPeminjamanController::class, 'returnBook'])->name('peminjaman.return');
});

