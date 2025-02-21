<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


// Primer modulo
Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

