<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;


// Primer modulo - Autenticacion
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Segundo modulo - Tiendas
Route::get('/store/show/', [StoreController::class, 'index'])->middleware('auth:sanctum');
Route::get('/store/find/{store}', [StoreController::class, 'show'])->middleware('auth:sanctum');

Route::post('/store/create/', [StoreController::class, 'store'])->middleware('auth:sanctum', 'is_seller');
Route::put('/store/update/{store}', [StoreController::class, 'update'])->middleware('auth:sanctum', 'is_seller');
Route::delete('/store/delete/{store}', [StoreController::class, 'destroy'])->middleware('auth:sanctum', 'is_seller');

// Tercer modulo - Productos
Route::get('/product/show/', [ProductController::class, 'index'])->middleware('auth:sanctum');
Route::get('/product/find/{product}', [ProductController::class, 'show'])->middleware('auth:sanctum');

Route::post('/product/create/', [ProductController::class, 'store'])->middleware('auth:sanctum', 'is_seller');
Route::put('/product/update/{product}', [ProductController::class, 'update'])->middleware('auth:sanctum', 'is_seller');
Route::delete('/product/delete/{product}', [ProductController::class, 'destroy'])->middleware('auth:sanctum', 'is_seller');

// Cuarto modulo - Carrito
Route::get('/cart/show/', [CartItemController::class, 'index'])->middleware('auth:sanctum', 'is_client');

Route::post('/cart/add/', [CartItemController::class, 'addToCart'])->middleware('auth:sanctum', 'is_client');
Route::post('/cart/remove/', [CartItemController::class, 'removeFromCart'])->middleware('auth:sanctum', 'is_client');

// Quinto modulo - Finalizar compra
Route::post('/cart/pay/', [CartItemController::class, 'payCart'])->middleware('auth:sanctum', 'is_client');
