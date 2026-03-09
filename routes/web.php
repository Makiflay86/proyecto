<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/* Route::get('/', function () {
    return view('welcome');
}); */

Route::get('/', function () {
    $dailyMessage = \App\Models\DailyMessage::whereDate('date', today())->first();

    $totalProducts   = \App\Models\Product::count();
    $activeProducts  = \App\Models\Product::where('estado', 'activo')->count();
    $inactiveProducts = $totalProducts - $activeProducts;
    $categoriesCount = \App\Models\Product::distinct('categoria')->count('categoria');

    $recentProducts = \App\Models\Product::latest()->take(5)->get();

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
