<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PublishController extends Controller
{
    public function create()
    {
        $categoryOptions = Category::flatOptions();

        return view('store.publish', compact('categoryOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio'      => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*'    => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $product = Product::create($validated + ['user_id' => Auth::id()]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('productos', 'public');
                    $product->images()->create(['path' => $path]);
                }
            }
        });

        return redirect()->route('profile.store')->with('success', '¡Producto publicado correctamente!');
    }
}
