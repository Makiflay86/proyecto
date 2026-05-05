<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * Controlador que gestiona las operaciones CRUD sobre categorías (panel admin).
 *
 * Rutas asociadas (todas requieren auth + verified + admin):
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
    public function index()
    {
        $categories = Category::whereNull('parent_id')->with('allChildren')->orderBy('name')->get();

        $productCounts = [];
        foreach ($categories as $category) {
            $productCounts[$category->id] = Product::whereIn('category_id', $category->allDescendantIds())->count();
        }

        return view('admin.categories.index', compact('categories', 'productCounts'));
    }

    public function create()
    {
        $categoryOptions = Category::flatOptions();

        return view('admin.categories.create', compact('categoryOptions'));
    }

    public function store(Request $request)
    {
        $parentId = $request->parent_id ?: null;

        $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('categories', 'name')->where(function ($query) use ($parentId) {
                    return $parentId
                        ? $query->where('parent_id', $parentId)
                        : $query->whereNull('parent_id');
                }),
            ],
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

    public function show(Category $category)
    {
        $category->load(['parent.parent.parent.parent.parent', 'children.allChildren']);

        $totalProductCount  = Product::whereIn('category_id', $category->allDescendantIds())->count();
        $directProductCount = $category->products()->count();

        $categoryPath = [];
        $ancestors    = [];
        $cat = $category;
        while ($cat) {
            array_unshift($categoryPath, $cat->id);
            if ($cat->id !== $category->id) {
                array_unshift($ancestors, $cat);
            }
            $cat = $cat->parent;
        }

        $childProductCounts = [];
        $childPaths         = [];
        foreach ($category->children as $child) {
            $childProductCounts[$child->id] = Product::whereIn('category_id', $child->allDescendantIds())->count();
            $childPaths[$child->id]         = array_merge($categoryPath, [$child->id]);
        }

        return view('admin.categories.show', compact(
            'category',
            'totalProductCount',
            'directProductCount',
            'childProductCounts',
            'childPaths',
            'categoryPath',
            'ancestors'
        ));
    }

    public function edit(Category $category)
    {
        $categoryOptions = Category::flatOptions($category->id);

        return view('admin.categories.edit', compact('category', 'categoryOptions'));
    }

    public function update(Request $request, Category $category)
    {
        $parentId = $request->parent_id ?: null;

        $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('categories', 'name')
                    ->ignore($category->id)
                    ->where(function ($query) use ($parentId) {
                        return $parentId
                            ? $query->where('parent_id', $parentId)
                            : $query->whereNull('parent_id');
                    }),
            ],
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $data = [
            'name'      => $request->name,
            'parent_id' => $request->parent_id ?: null,
        ];

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categorias', 'public');
        }

        if ($request->boolean('remove_image') && $category->image) {
            Storage::disk('public')->delete($category->image);
            $data['image'] = null;
        }

        $category->update($data);

        return redirect()->route('categories.show', $category)
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category)
    {
        $category->load('allChildren');
        $allIds = array_merge([$category->id], $this->collectIds($category->allChildren));

        Product::whereIn('category_id', $allIds)->with('images')->get()->each(function ($product) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->path);
            }
        });

        Category::whereIn('id', $allIds)->whereNotNull('image')->get()->each(function ($cat) {
            Storage::disk('public')->delete($cat->image);
        });

        $category->delete();

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
