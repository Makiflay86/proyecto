<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index()
    {
        return view('shop.index');
    }

    public function favorites()
    {
        $products = Auth::user()
            ->likedProducts()
            ->with(['images', 'category.parent'])
            ->latest('product_likes.created_at')
            ->get();

        return view('shop.favorites', compact('products'));
    }

    public function show(Product $product)
    {
        abort_if($product->estado === 'inactivo', 404);

        $product->load(['category.parent', 'images', 'user']);

        return view('shop.show', compact('product'));
    }
}
