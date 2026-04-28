<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index()
    {
        return view('store.index');
    }

    public function favorites()
    {
        $products = Auth::user()
            ->likedProducts()
            ->with(['images', 'category.parent'])
            ->latest('product_likes.created_at')
            ->get();

        return view('store.favorites', compact('products'));
    }

    public function show(Product $product)
    {
        abort_if($product->estado === 'inactivo', 404);

        $product->load(['category.parent', 'images', 'user']);

        return view('store.show', compact('product'));
    }
}
