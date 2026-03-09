<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
| Ruta raíz protegida por autenticación y verificación de email.
| Prepara todos los datos necesarios para las estadísticas, gráficos y
| la tabla de productos recientes del dashboard.
*/
Route::get('/', function () {
    // Mensaje del día: busca el registro de DailyMessage cuya fecha sea hoy
    $dailyMessage = \App\Models\DailyMessage::whereDate('date', today())->first();

    // Estadísticas generales de productos para las cards del dashboard
    $totalProducts    = \App\Models\Product::count();
    $activeProducts   = \App\Models\Product::where('estado', 'activo')->count();
    $inactiveProducts = $totalProducts - $activeProducts;
    $categoriesCount  = \App\Models\Product::distinct('categoria')->count('categoria');

    // Últimos 5 productos creados para la tabla de recientes
    $recentProducts = \App\Models\Product::latest()->take(5)->get();

    // Agrupación por categoría para el gráfico de barras
    // Resultado: Collection { 'electronica' => 3, 'hogar' => 5, ... }
    $productsByCategory = \App\Models\Product::selectRaw('categoria, COUNT(*) as total')
        ->groupBy('categoria')
        ->pluck('total', 'categoria');

    return view('dashboard', [
        'message'            => $dailyMessage?->message,
        'totalProducts'      => $totalProducts,
        'activeProducts'     => $activeProducts,
        'inactiveProducts'   => $inactiveProducts,
        'categoriesCount'    => $categoriesCount,
        'recentProducts'     => $recentProducts,
        'productsByCategory' => $productsByCategory,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

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
