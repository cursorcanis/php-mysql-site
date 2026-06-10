<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ShareController;
use Illuminate\Support\Facades\Route;

// --- Auth (single user) ---
Route::get('/', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Authenticated app ---
Route::middleware('vault.auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/upload', [FileController::class, 'store'])->name('files.store');
    Route::delete('/file/{file}', [FileController::class, 'destroy'])->name('files.destroy');
    Route::post('/api', [ApiController::class, 'handle'])->name('api');
});

// --- File serving (unguessable random filename; powers previews + downloads + docx viewer) ---
Route::get('/file/{name}', [FileController::class, 'show'])->name('files.show');
Route::get('/file/{name}/download', [FileController::class, 'download'])->name('files.download');

// --- Public share page ---
Route::get('/s/{token}', [ShareController::class, 'show'])->name('share');
