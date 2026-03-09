<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
| Toda la lógica de datos vive en DashboardController@index.
*/
Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Productos
|--------------------------------------------------------------------------
| CRUD de productos. Todas las rutas requieren autenticación y email verificado.
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/products',         [ProductController::class, 'index'])->name('products.index');
    Route::post('/products',        [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/create',  [ProductController::class, 'create'])->name('products.create');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
});

/*
|--------------------------------------------------------------------------
| Perfil de usuario
|--------------------------------------------------------------------------
| Edición, actualización y eliminación del perfil. Solo requiere autenticación.
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas de autenticación generadas por Laravel Breeze (login, registro, etc.)
require __DIR__.'/auth.php';
