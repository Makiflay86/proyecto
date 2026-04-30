<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Componente Livewire que muestra la lista de productos con filtro de categorías
 * en modo "drill-down" (navegación por niveles: raíz → hijo → nieto...).
 */
class ProductList extends Component
{
    use WithPagination;

    /**
     * Array que representa el camino de categorías seleccionadas desde la raíz.
     */
    #[Url(as: 'path')]
    public array $path = [];

    /**
     * Propiedad para controlar el orden de los productos.
     */
    #[Url]
    public string $orden = '';

    /**
     * Se ejecuta automáticamente cuando el componente se inicializa.
     */
    public function mount(): void
    {
        $this->path = array_map('intval', $this->path);
    }

    /**
     * Resetea la página cuando cambia el orden.
     */
    public function updatedOrden(): void
    {
        $this->resetPage();
    }

    /**
     * Selecciona una categoría y limpia los niveles más profundos.
     */
    public function selectLevel(int $depth, int $id): void
    {
        $this->path = array_slice($this->path, 0, $depth);
        $this->path[] = $id;
        $this->resetPage();
    }

    /**
     * Limpia la selección desde el nivel indicado en adelante.
     */
    public function clearFrom(int $depth): void
    {
        $this->path = array_slice($this->path, 0, $depth);
        $this->resetPage();
    }

    /**
     * Renderiza el componente. Se ejecuta en cada cambio de estado (cada wire:click).
     *
     * Construye dos cosas:
     *
     * 1. $categoryRows: las filas de botones de filtro.
     *    - Fila 0: siempre muestra las categorías raíz.
     *    - Fila 1: los hijos de la categoría seleccionada en depth 0 (si los tiene).
     *    - Fila 2: los hijos de la seleccionada en depth 1... y así sucesivamente.
     *    Solo se añade una fila si la categoría seleccionada tiene hijos.
     *
     * 2. $products: los productos filtrados.
     *    Si no hay selección → todos los productos.
     *    Si hay selección → productos de la última categoría seleccionada y todas sus subcategorías.
     */
    public function render()
    {
        // --- Construir las filas de filtros (drill-down) ---

        $categoryRows = [];
        // La primera fila siempre son las categorías raíz
        $categoryRows[] = Category::whereNull('parent_id')->orderBy('name')->get(['id', 'name']);

        // Por cada categoría seleccionada en el path, añadimos una nueva fila con sus hijos
        foreach ($this->path as $selectedId) {
            $children = Category::where('parent_id', $selectedId)->orderBy('name')->get(['id', 'name']);
            if ($children->isNotEmpty()) {
                $categoryRows[] = $children; // Solo añadimos la fila si tiene hijos
            } else {
                break; // Si no tiene hijos, no hay más niveles que mostrar
            }
        }

        // --- Determinar qué categorías usar para filtrar los productos ---

        // Si hay path, filtramos por la última categoría seleccionada y todos sus descendientes
        // Ejemplo: path = [MOTOR, COCHE] → filtramos por COCHE, SEDAN, SUV (hijos de COCHE)
        $filterIds = [];
        if (!empty($this->path)) {
            $lastId = end($this->path); // Última categoría seleccionada
            $cat = Category::with('allChildren')->find($lastId);
            $filterIds = array_merge([$lastId], $this->collectIds($cat->allChildren));
        }

        // --- Construir la consulta de productos ---

        // Se carga la relación category con todos sus padres (para mostrar la categoría raíz)
        $query = Product::with(['images', 'category.parent.parent.parent.parent'])
            ->latest();

        if (!empty($filterIds)) {
            $query->whereIn('products.category_id', $filterIds);
        }

        // Aplicar orden si existe
        if ($this->orden === 'precio_asc') {
            $query->reorder()->orderBy('precio', 'asc');
        } elseif ($this->orden === 'precio_desc') {
            $query->reorder()->orderBy('precio', 'desc');
        }

        // Paginamos los productos (12 por página)
        $products = $query->paginate(12);

        return view('livewire.product-list', compact('products', 'categoryRows'));
    }

    /**
     * Método auxiliar privado para recopilar recursivamente todos los IDs
     * de una colección de subcategorías y sus descendientes.
     *
     * Se usa en render() para determinar qué productos mostrar cuando
     * el filtro activo tiene subcategorías propias.
     */
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
