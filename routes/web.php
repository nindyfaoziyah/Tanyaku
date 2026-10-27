<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatasetController;

// Redirect halaman utama ke login
Route::redirect('/', '/login');

// Route untuk pengguna yang belum login
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1');
});

// Route untuk pengguna yang sudah login
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Pengujian hak akses
    Route::get('/access-test', function () {
        $user = auth()->user();

        return response()->json([
            'message' => 'Akses berhasil diberikan.',
            'user' => $user->name,
            'role' => $user->role?->name,
        ]);
    })
        ->middleware('permission:dashboard.view')
        ->name('access.test');

    // Menampilkan daftar dataset
    Route::get('/datasets', [DatasetController::class, 'index'])
        ->middleware('permission:dataset.view')
        ->name('datasets.index');

    // Membuat dataset
    Route::get('/datasets/create', [DatasetController::class, 'create'])
        ->middleware('permission:dataset.create')
        ->name('datasets.create');

    Route::post('/datasets', [DatasetController::class, 'store'])
        ->middleware('permission:dataset.create')
        ->name('datasets.store');

    // Mengedit dataset
    Route::get('/datasets/{dataset}/edit', [DatasetController::class, 'edit'])
        ->middleware('permission:dataset.update')
        ->name('datasets.edit');

    Route::put('/datasets/{dataset}', [DatasetController::class, 'update'])
        ->middleware('permission:dataset.update')
        ->name('datasets.update');

    // Menghapus dataset
    Route::delete('/datasets/{dataset}', [DatasetController::class, 'destroy'])
        ->middleware('permission:dataset.delete')
        ->name('datasets.destroy');

    // Menampilkan detail dataset
    Route::get('/datasets/{dataset}', [DatasetController::class, 'show'])
        ->middleware('permission:dataset.view')
        ->name('datasets.show');

});

Route::redirect('/', '/login');