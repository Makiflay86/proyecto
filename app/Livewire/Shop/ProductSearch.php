<?php

namespace App\Livewire\Shop;

use App\Models\Product;
use Livewire\Component;

/**
 * Componente Livewire de búsqueda en tiempo real.
 * Busca productos por nombre, descripción y categoría mientras el usuario escribe.
 * Se incluye en el sidebar con: <livewire:shop.product-search />
 */
class ProductSearch extends Component
{
    public string $query = '';
    public array $results = [];
    public bool $open = false;

    public function updatedQuery(): void
    {
        if (strlen($this->query) < 2) {
            $this->results = [];
            $this->open    = false;
            return;
        }

        $this->results = Product::whereIn('estado', ['activo', 'reservado'])
            ->where(function($q) {
                $q->where('nombre', 'like', "%{$this->query}%")
                  ->orWhere('descripcion', 'like', "%{$this->query}%")
                  ->orWhereHas('category', fn ($cat) => $cat->where('name', 'like', "%{$this->query}%"));
            })
            ->with('category')
            ->limit(6)
            ->get(['id', 'nombre', 'category_id', 'precio'])
            ->toArray();

        $this->open = true;
    }

    public function cerrar(): void
    {
        $this->open  = false;
        $this->query = '';
        $this->results = [];
    }

    public function render()
    {
        return view('livewire.shop.product-search');
    }
}
