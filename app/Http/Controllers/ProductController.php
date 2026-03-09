<?php

namespace App\Http\Controllers;

use App\Events\ProductCreated;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador que gestiona las operaciones sobre productos.
 * Rutas asociadas (todas requieren auth + verified):
 *   GET    /products                → index()
 *   GET    /products/create         → create()
 *   POST   /products                → store()
 *   GET    /products/{product}      → show()
 *   GET    /products/{product}/edit → edit()
 *   PUT    /products/{product}      → update()
 *   DELETE /products/{product}      → destroy()
 */
class ProductController extends Controller
{
    /** Lista todos los productos (el filtro reactivo lo gestiona el componente Livewire ProductList). */
    public function index()
    {
        return view('products.index');
    }

    /** Muestra el formulario para crear un nuevo producto. */
    public function create()
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('products.create', compact('categories'));
    }

    /** Valida y guarda un nuevo producto junto con sus imágenes. */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio'      => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*'    => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = $request->user();

        $product = DB::transaction(function () use ($request, $validatedData) {
            $product = Product::create($validatedData);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('productos', 'public');
                    $product->images()->create(['path' => $path]);
                }
            }

            return $product;
        });

        ProductCreated::dispatch($product, $user);

        return redirect()->route('products.index')
            ->with('success', 'Producto creado correctamente.');
    }

    /** Muestra el detalle de un producto. */
    public function show(Product $product)
    {
        $product->load(['images', 'category']);

        return view('products.show', compact('product'));
    }

    /** Muestra el formulario para editar un producto existente. */
    public function edit(Product $product)
    {
        $product->load('images');
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('products.edit', compact('product', 'categories'));
    }

    /** Valida y actualiza un producto. Las imágenes nuevas se añaden a las existentes. */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio'      => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*'    => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::transaction(function () use ($request, $product) {
            $product->update($request->only('nombre', 'descripcion', 'precio', 'category_id', 'estado'));

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

        return redirect()->route('products.show', $product)
            ->with('success', 'Producto actualizado correctamente.');
    }

    /** Elimina un producto y sus imágenes del storage. */
    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
