<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador que gestiona las operaciones sobre categorías.
 * Rutas asociadas (todas requieren auth + verified):
 *   GET    /categories                  → index()
 *   GET    /categories/create           → create()
 *   POST   /categories                  → store()
 *   GET    /categories/{category}       → show()
 *   GET    /categories/{category}/edit  → edit()
 *   PUT    /categories/{category}       → update()
 *   DELETE /categories/{category}       → destroy()
 */
class CategoryController extends Controller
{
    /** Lista todas las categorías ordenadas por nombre. */
    public function index()
    {
        $categories = Category::orderBy('name')->get();

        return view('categories.index', compact('categories'));
    }

    /** Muestra el formulario para crear una nueva categoría. */
    public function create()
    {
        return view('categories.create');
    }

    /** Valida y guarda una nueva categoría con imagen opcional. */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = ['name' => $request->name];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categorias', 'public');
        }

        Category::create($data);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    /** Muestra el detalle de una categoría. */
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    /** Muestra el formulario para editar una categoría. */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /** Valida y actualiza una categoría. Si se sube imagen nueva, borra la anterior. */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'  => "required|string|max:255|unique:categories,name,{$category->id}",
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = ['name' => $request->name];

        if ($request->hasFile('image')) {
            // Borrar imagen anterior del storage si existía
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categorias', 'public');
        }

        // Opción de quitar la imagen actual sin subir una nueva
        if ($request->boolean('remove_image') && $category->image) {
            Storage::disk('public')->delete($category->image);
            $data['image'] = null;
        }

        $category->update($data);

        return redirect()->route('categories.show', $category)
            ->with('success', 'Categoría actualizada correctamente.');
    }

    /** Elimina una categoría, su imagen y todos los productos asociados con sus imágenes. */
    public function destroy(Category $category)
    {
        // Eliminar todos los productos que pertenecen a esta categoría
        // junto con sus archivos físicos de imágenes en storage
        Product::where('categoria', $category->name)
            ->with('images')
            ->get()
            ->each(function (Product $product) {
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image->path);
                }
                $product->delete();
            });

        // Eliminar la imagen de la categoría si tiene
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}
