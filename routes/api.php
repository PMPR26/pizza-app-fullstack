<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PizzaController;
use App\Http\Controllers\Api\IngredientController;
use App\Http\Controllers\Api\OrderController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    // Rutas admin: middleware de rol
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('ingredients', IngredientController::class);
        Route::apiResource('pizzas', PizzaController::class);
        Route::get('orders', [OrderController::class, 'index']); // listado completo pedidos
    });

    // Rutas de usuario
    Route::post('orders', [OrderController::class, 'store']); // crear pedido
    Route::get('orders/{order}', [OrderController::class, 'show']); // ver pedido propio

});