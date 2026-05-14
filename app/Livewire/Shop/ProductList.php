<?php

namespace App\Livewire\Shop;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Url;
use Livewire\Component;

class ProductList extends Component
{
    #[Url(as: 'path')]
    public array $path = [];

    #[Url]
    public string $buscar = '';

    #[Url]
    public string $orden = '';

    #[Url]
    public string $precioMin = '';

    #[Url]
    public string $precioMax = '';

    public int $perPage = 30;
    public bool $hasMore = false;

    public function mount(): void
    {
        $this->path = array_map('intval', $this->path);
    }

    public function loadMore(): void
    {
        $this->perPage += 30;
    }

    public function selectLevel(int $depth, int $id): void
    {
        $this->path = array_slice($this->path, 0, $depth);
        $this->path[] = $id;
        $this->perPage = 30;
    }

    public function clearFrom(int $depth): void
    {
        $this->path = array_slice($this->path, 0, $depth);
        $this->perPage = 30;
    }

    public function updatedOrden(): void
    {
        $this->perPage = 30;
    }

    public function updatedBuscar(): void
    {
        $this->perPage = 30;
    }

    public function updatedPrecioMin(): void
    {
        $this->perPage = 30;
    }

    public function updatedPrecioMax(): void
    {
        $this->perPage = 30;
    }

    public function clearAll(): void
    {
        $this->path      = [];
        $this->buscar    = '';
        $this->precioMin = '';
        $this->precioMax = '';
        $this->perPage   = 30;
    }

    public function render()
    {
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

        $filterIds = [];
        if (!empty($this->path)) {
            $lastId = end($this->path);
            $cat = Category::with('allChildren')->find($lastId);
            $filterIds = array_merge([$lastId], $this->collectIds($cat->allChildren));
        }

        $query = Product::whereIn('estado', ['activo', 'reservado'])
            ->with(['category', 'images', 'user'])
            ->latest();

        if (!empty($filterIds)) {
            $query->whereIn('category_id', $filterIds);
        }

        if (!empty($this->buscar)) {
            $buscar = $this->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('descripcion', 'like', "%{$buscar}%");
            });
        }

        if ($this->precioMin !== '') {
            $query->where('precio', '>=', (float) $this->precioMin);
        }

        if ($this->precioMax !== '') {
            $query->where('precio', '<=', (float) $this->precioMax);
        }

        if ($this->orden === 'precio_asc') {
            $query->reorder()->orderBy('precio', 'asc');
        } elseif ($this->orden === 'precio_desc') {
            $query->reorder()->orderBy('precio', 'desc');
        }

        $total = $query->count();
        $products = $query->take($this->perPage)->get();
        $this->hasMore = $total > $products->count();

        return view('livewire.shop.product-list', compact('products', 'categoryRows', 'total'));
    }

    private function collectIds(\Illuminate\Support\Collection $children): array
    {
        $ids = [];
        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->collectIds($child->allChildren));
        }
        return $ids;
    }
}
