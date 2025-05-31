<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\PengaduanController;
use App\Http\Controllers\Api\TanggapanController;
use App\Http\Controllers\Api\KategoriController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/pengaduan/all', [PengaduanController::class, 'all']);
    Route::get('/pengaduan/{id}/tanggapan', [TanggapanController::class, 'byPengaduan']);
    Route::get('/tanggapan', [TanggapanController::class, 'index']); 
    Route::post('/tanggapan', [TanggapanController::class, 'store']);  
});

Route::middleware(['auth:sanctum', 'role:user,admin'])->group(function () {
    Route::get('/pengaduan', [PengaduanController::class, 'index']);
    Route::get('/pengaduan/me', [PengaduanController::class, 'myPengaduan']);
    Route::post('/pengaduan', [PengaduanController::class, 'store']);
    Route::get('/pengaduan/{id}', [PengaduanController::class, 'show']);
    Route::put('/pengaduan/{id}', [PengaduanController::class, 'update']);
    Route::delete('/pengaduan/{id}', [PengaduanController::class, 'destroy']);

    // Pindahkan ini ke sini:
    Route::get('/pengaduan/{id}/tanggapan', [TanggapanController::class, 'byPengaduan']);
});

Route::get('/kategori', [KategoriController::class, 'index']);
Route::post('/pengaduan-guest', [PengaduanController::class, 'storeAsGuest']);