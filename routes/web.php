<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tienda pública (sin autenticación)
|--------------------------------------------------------------------------
*/
Route::get('/', [StoreController::class, 'index'])->name('store.index');
Route::get('/producto/{product}', [StoreController::class, 'show'])->name('store.show');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
| Toda la lógica de datos vive en DashboardController@index.
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Productos
|--------------------------------------------------------------------------
| CRUD de productos. Todas las rutas requieren autenticación y email verificado.
*/
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/products',                  [ProductController::class, 'index'])->name('products.index');
    Route::post('/products',                 [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/create',           [ProductController::class, 'create'])->name('products.create');
    Route::get('/products/{product}',        [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit',   [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}',        [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}',     [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/categories',                    [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories',                   [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/create',             [CategoryController::class, 'create'])->name('categories.create');
    Route::get('/categories/{category}',         [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/categories/{category}/edit',    [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}',         [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}',      [CategoryController::class, 'destroy'])->name('categories.destroy');
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
