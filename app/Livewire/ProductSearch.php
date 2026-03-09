<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

/**
 * Componente Livewire de búsqueda en tiempo real.
 * Busca productos por nombre, descripción y categoría mientras el usuario escribe.
 * Se incluye en el sidebar con: <livewire:product-search />
 */
class ProductSearch extends Component
{
    // Lo que el usuario está escribiendo (enlazado al input con wire:model.live.debounce.300ms)
    public string $query = '';

    // Resultados encontrados
    public array $results = [];

    // Controla si el desplegable está visible
    public bool $open = false;

    /**
     * Se ejecuta automáticamente cada vez que cambia $query.
     * El debounce de 300ms en la vista evita lanzar una consulta por cada tecla.
     */
    public function updatedQuery(): void
    {
        if (strlen($this->query) < 2) {
            $this->results = [];
            $this->open    = false;
            return;
        }

        // Busca en nombre, descripción y categoría (LIKE insensible a mayúsculas)
        $this->results = Product::where('nombre', 'like', "%{$this->query}%")
            ->orWhere('descripcion', 'like', "%{$this->query}%")
            ->orWhere('categoria', 'like', "%{$this->query}%")
            ->limit(6)
            ->get(['id', 'nombre', 'descripcion', 'categoria', 'precio'])
            ->toArray();

        $this->open = true;
    }

    /**
     * Cierra el desplegable (llamado desde la vista con wire:click o al pulsar Escape).
     */
    public function cerrar(): void
    {
        $this->open  = false;
        $this->query = '';
        $this->results = [];
    }

    public function render()
    {
        return view('livewire.product-search');
    }
}
