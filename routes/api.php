<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;


// Primer modulo - Autenticacion
Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Segundo modulo - Tiendas
Route::get('/store/show/', [StoreController::class, 'index'])->middleware('auth:sanctum');
Route::get('/store/find/{store}', [StoreController::class, 'show'])->middleware('auth:sanctum');

Route::post('/store/create/', [StoreController::class, 'store'])->middleware('auth:sanctum', 'is_seller');
Route::put('/store/update/{store}', [StoreController::class, 'update'])->middleware('auth:sanctum', 'is_seller');
Route::delete('/store/delete/{store}', [StoreController::class, 'destroy'])->middleware('auth:sanctum', 'is_seller');

