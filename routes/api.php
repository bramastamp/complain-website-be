<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\PengaduanController;
use App\Http\Controllers\Api\TanggapanController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::put('/pengaduan/{id}/status', [PengaduanController::class, 'updateStatus']);
    Route::post('/tanggapan/{pengaduan_id}', [TanggapanController::class, 'store']); // opsional
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

Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return $request->user();
});