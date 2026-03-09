<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class ProductList extends Component
{
    public ?int $categoryFilter = null;

    public function setCategory(?int $id): void
    {
        $this->categoryFilter = $id;
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);

        $query = Product::with(['images', 'category'])
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->select('products.*');

        if ($this->categoryFilter) {
            $query->where('products.category_id', $this->categoryFilter);
        }

        $products = $query
            ->orderBy('categories.name')
            ->orderBy('products.nombre')
            ->get();

        return view('livewire.product-list', compact('products', 'categories'));
    }
}
