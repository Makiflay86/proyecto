<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class ProductList extends Component
{
    /** Drill-down path: array of category IDs from root to deepest selected. */
    public array $path = [];

    /** Select a category at the given depth, clearing deeper selections. */
    public function selectLevel(int $depth, int $id): void
    {
        $this->path = array_slice($this->path, 0, $depth);
        $this->path[] = $id;
    }

    /** Clear selections from the given depth onwards. */
    public function clearFrom(int $depth): void
    {
        $this->path = array_slice($this->path, 0, $depth);
    }

    public function render()
    {
        // Build drill-down rows: level 0 = roots, level 1 = children of path[0], etc.
        $categoryRows = [];
        $categoryRows[] = Category::whereNull('parent_id')->orderBy('name')->get(['id', 'name']);

        foreach ($this->path as $selectedId) {
            $children = Category::where('parent_id', $selectedId)->orderBy('name')->get(['id', 'name']);
            if ($children->isNotEmpty()) {
                $categoryRows[] = $children;
            } else {
                break;
            }
        }

        // Determine which category IDs to filter by (last selected + all its descendants)
        $filterIds = [];
        if (!empty($this->path)) {
            $lastId = end($this->path);
            $cat = Category::with('allChildren')->find($lastId);
            $filterIds = array_merge([$lastId], $this->collectIds($cat->allChildren));
        }

        $query = Product::with(['images', 'category.parent.parent.parent.parent'])
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->select('products.*');

        if (!empty($filterIds)) {
            $query->whereIn('products.category_id', $filterIds);
        }

        $products = $query
            ->get()
            ->sortBy(fn ($p) => ($p->category?->root->name ?? '') . '|' . $p->nombre)
            ->values();

        return view('livewire.product-list', compact('products', 'categoryRows'));
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
