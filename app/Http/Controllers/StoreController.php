<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::activos()
            ->with(['category', 'images', 'user'])
            ->latest();

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('descripcion', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('categoria')) {
            $category = Category::with('allChildren')->find($request->categoria);
            if ($category) {
                $query->whereIn('category_id', $category->allDescendantIds());
            }
        }

        if ($request->orden === 'precio_asc') {
            $query->reorder()->orderBy('precio', 'asc');
        } elseif ($request->orden === 'precio_desc') {
            $query->reorder()->orderBy('precio', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('store.index', compact('products', 'categories'));
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
        abort_if($product->estado !== 'activo', 404);

        $product->load(['category.parent', 'images', 'user']);

        return view('store.show', compact('product'));
    }
}
