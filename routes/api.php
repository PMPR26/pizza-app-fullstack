<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController; // crea este controller
use App\Http\Controllers\Api\AdminController; // crea este controller para admin

// Registro y login
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Info del usuario logueado
    Route::get('me', [UserController::class, 'me']);

    // Logout
    Route::post('logout', [AuthController::class, 'logout']);

    // Rutas de usuario
    Route::get('user/dashboard', [UserController::class, 'dashboard']);

    // Rutas de admin
    Route::middleware('role:admin')->group(function () {
        Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
        Route::apiResource('pizzas', AdminController::class);        // CRUD de pizzas
        Route::apiResource('ingredients', AdminController::class);   // CRUD de ingredientes
        Route::get('orders', [AdminController::class, 'orders']);    // listado de pedidos
    });

    // Rutas de pedidos
    Route::post('orders', [UserController::class, 'createOrder']); // usuarios normales hacen pedidos
});