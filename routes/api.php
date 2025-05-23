<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\PengaduanController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    // Route::post('/tanggapan', [TanggapanController::class, 'store']);
    // route khusus admin lainnya
});

Route::middleware('auth:sanctum', 'role:user,admin')->group(function () {
    Route::get('/pengaduan', [PengaduanController::class, 'index']);
    Route::post('/pengaduan', [PengaduanController::class, 'store']);
    Route::get('/pengaduan/{id}', [PengaduanController::class, 'show']);
    Route::put('/pengaduan/{id}', [PengaduanController::class, 'update']);
    Route::delete('/pengaduan/{id}', [PengaduanController::class, 'destroy']);
});