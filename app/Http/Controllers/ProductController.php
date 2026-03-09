<?php

namespace App\Http\Controllers;

use App\Events\ProductCreated;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    /**
     * Muestra el formulario para crear un nuevo producto.
     * Vista: resources/views/products/create.blade.php
     */
    public function create()
    {
        $categories = Category::orderBy('name')->pluck('name');

        return view('products.create', compact('categories'));
    }

    /**
     * Muestra el detalle de un producto concreto.
     * Laravel resuelve automáticamente el Product por el {product} de la URL.
     * Vista: resources/views/products/show.blade.php
     */
    public function show(Product $product)
    {
        $product->load('images');

        return view('products.show', compact('product'));
    }

    /**
     * Lista todos los productos con sus imágenes.
     * Usa eager loading (with) para cargar las imágenes en una sola consulta
     * y evitar el problema N+1 (una consulta extra por cada producto).
     * Vista: resources/views/products/index.blade.php
     */
    public function index()
    {
        $products = Product::with('images')->latest()->get();

        return view('products.index', compact('products'));
    }

    /**
     * Valida y guarda un nuevo producto junto con sus imágenes.
     *
     * Flujo:
     *  1. Valida los datos del formulario (falla automáticamente si no pasan)
     *  2. Abre una transacción: si algo falla a mitad, todo se revierte
     *  3. Crea el producto en la BD
     *  4. Si hay imágenes, las guarda en storage/app/public/productos
     *     y registra la ruta en la tabla product_images
     *  5. Redirige al listado con mensaje de éxito
     */
    public function store(Request $request)
    {
        // Validación: si falla, Laravel redirige automáticamente con los errores
        $validatedData = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio'      => 'required|numeric|min:0',
            'categoria'   => 'required|string',
            'images.*'    => 'image|mimes:jpeg,png,jpg|max:2048', // max 2MB por imagen
        ]);

        $user = $request->user();

        // Transacción: si falla al guardar alguna imagen, el producto también se revierte.
        // La transacción devuelve el producto para usarlo fuera.
        $product = DB::transaction(function () use ($request, $validatedData) {

            $product = Product::create($validatedData);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    // Guarda el archivo en storage/app/public/productos y obtiene la ruta
                    $path = $image->store('productos', 'public');

                    // Registra la ruta en la tabla product_images vinculada a este producto
                    $product->images()->create(['path' => $path]);
                }
            }

            return $product;
        });

        // El evento se dispara FUERA de la transacción para que un fallo en el email
        // no revierta la creación del producto. El producto ya está guardado aquí.
        ProductCreated::dispatch($product, $user);

        return redirect()->route('products.index')
            ->with('success', 'Producto creado correctamente.');
    }

    /** Muestra el formulario para editar un producto existente. */
    public function edit(Product $product)
    {
        $product->load('images');
        $categories = Category::orderBy('name')->pluck('name');

        return view('products.edit', compact('product', 'categories'));
    }

    /** Valida y actualiza un producto. Las imágenes nuevas se añaden a las existentes. */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio'      => 'required|numeric|min:0',
            'categoria'   => 'required|string',
            'images.*'    => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::transaction(function () use ($request, $product) {
            $product->update($request->only('nombre', 'descripcion', 'precio', 'categoria', 'estado'));

            // Eliminar las imágenes marcadas con el botón X en el formulario
            if ($request->filled('delete_images')) {
                $toDelete = $product->images()->whereIn('id', $request->delete_images)->get();
                foreach ($toDelete as $img) {
                    \Storage::disk('public')->delete($img->path);
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

    /** Elimina un producto y sus imágenes asociadas del storage. */
    public function destroy(Product $product)
    {
        // Eliminar archivos físicos del storage antes de borrar el registro
        foreach ($product->images as $image) {
            \Storage::disk('public')->delete($image->path);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
