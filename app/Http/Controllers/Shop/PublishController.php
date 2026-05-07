<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PublishController extends Controller
{
    public function create()
    {
        $categoryOptions = Category::flatOptions();

        return view('shop.publish', compact('categoryOptions'));
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

        return redirect()->route('store.index')->with('success', '¡Producto publicado correctamente!');
    }

    public function edit(Product $product)
    {
        abort_if($product->user_id !== Auth::id(), 403);

        $product->load('images');
        $categoryOptions = Category::flatOptions();

        return view('shop.edit', compact('product', 'categoryOptions'));
    }

    public function update(Request $request, Product $product)
    {
        abort_if($product->user_id !== Auth::id(), 403);

        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio'      => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*'    => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::transaction(function () use ($request, $product) {
            $product->update($request->only('nombre', 'descripcion', 'precio', 'category_id'));

            if ($request->filled('delete_images')) {
                $toDelete = $product->images()->whereIn('id', $request->delete_images)->get();
                foreach ($toDelete as $img) {
                    Storage::disk('public')->delete($img->path);
                    $img->delete();
                }
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('productos', 'public');
                    $product->images()->create(['path' => $path]);
                }
            }
        });

        return redirect()->route('store.show', $product)->with('success', '¡Producto actualizado correctamente!');
    }

    public function destroy(Product $product)
    {
        abort_if($product->user_id !== Auth::id(), 403);

        $product->load('images');

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $product->delete();

        return redirect()->route('profile.store')->with('success', '¡Producto eliminado correctamente!');
    }

    public function markAsSold(Product $product)
    {
        abort_if($product->user_id !== Auth::id(), 403);

        $product->update(['estado' => 'vendido']);

        return back()->with('success', '¡Producto marcado como vendido!');
    }

    public function markAsReserved(Product $product)
    {
        abort_if($product->user_id !== Auth::id(), 403);

        $product->update(['estado' => 'reservado']);

        return back()->with('success', '¡Producto marcado como reservado!');
    }

    public function unreserve(Product $product)
    {
        abort_if($product->user_id !== Auth::id(), 403);

        $product->update(['estado' => 'activo']);

        return back()->with('success', '¡Reserva cancelada correctamente!');
    }

    public function reactivate(Product $product)
    {
        abort_if($product->user_id !== Auth::id(), 403);

        $product->update(['estado' => 'activo']);

        return back()->with('success', '¡Producto reactivado correctamente!');
    }
}
