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
    /** Lista solo las categorías raíz (sin padre). */
    public function index()
    {
        $categories = Category::whereNull('parent_id')->with('allChildren')->orderBy('name')->get();

        $productCounts = [];
        foreach ($categories as $category) {
            $productCounts[$category->id] = Product::whereIn('category_id', $category->allDescendantIds())->count();
        }

        return view('categories.index', compact('categories', 'productCounts'));
    }

    /** Muestra el formulario para crear una nueva categoría. */
    public function create()
    {
        $categoryOptions = Category::flatOptions();

        return view('categories.create', compact('categoryOptions'));
    }

    /** Valida y guarda una nueva categoría con imagen opcional. */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255|unique:categories,name',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $data = [
            'name'      => $request->name,
            'parent_id' => $request->parent_id ?: null,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categorias', 'public');
        }

        Category::create($data);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    /** Muestra el detalle de una categoría con sus subcategorías directas. */
    public function show(Category $category)
    {
        $category->load(['parent', 'children.allChildren']);

        $totalProductCount  = Product::whereIn('category_id', $category->allDescendantIds())->count();
        $directProductCount = $category->products()->count();

        $childProductCounts = [];
        foreach ($category->children as $child) {
            $childProductCounts[$child->id] = Product::whereIn('category_id', $child->allDescendantIds())->count();
        }

        // Build ancestor path array (root → current) for the products filter URL
        $categoryPath = [];
        $cat = $category;
        while ($cat) {
            array_unshift($categoryPath, $cat->id);
            $cat = $cat->parent;
        }

        return view('categories.show', compact('category', 'totalProductCount', 'directProductCount', 'childProductCounts', 'categoryPath'));
    }

    /** Muestra el formulario para editar una categoría. */
    public function edit(Category $category)
    {
        $categoryOptions = Category::flatOptions($category->id);

        return view('categories.edit', compact('category', 'categoryOptions'));
    }

    /** Valida y actualiza una categoría. Si se sube imagen nueva, borra la anterior. */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'      => "required|string|max:255|unique:categories,name,{$category->id}",
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $data = [
            'name'      => $request->name,
            'parent_id' => $request->parent_id ?: null,
        ];

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

    /** Elimina una categoría, su imagen, todas las subcategorías y los productos asociados con sus imágenes. */
    public function destroy(Category $category)
    {
        $category->load('allChildren');
        $allIds = array_merge([$category->id], $this->collectIds($category->allChildren));

        // Delete product image files
        Product::whereIn('category_id', $allIds)->with('images')->get()->each(function ($product) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->path);
            }
        });

        // Delete category image files
        Category::whereIn('id', $allIds)->whereNotNull('image')->get()->each(function ($cat) {
            Storage::disk('public')->delete($cat->image);
        });

        $category->delete(); // DB cascade deletes subcategories + their products

        return redirect()->route('categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }

    private function collectIds($children): array
    {
        $ids = [];
        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->collectIds($child->allChildren));
        }
        return $ids;
    }
}
