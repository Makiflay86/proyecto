<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Componente Livewire que muestra la lista de productos con filtro de categorías
 * en modo "drill-down" (navegación por niveles: raíz → hijo → nieto...).
 *
 * Livewire es un framework que permite componentes reactivos en PHP sin escribir
 * JavaScript. Cuando el usuario hace click en un botón con wire:click, Livewire
 * hace una petición AJAX al servidor, ejecuta el método PHP y actualiza solo
 * la parte del DOM que cambió (sin recargar la página).
 */
class ProductList extends Component
{
    /**
     * Array que representa el camino de categorías seleccionadas desde la raíz.
     * Cada posición (depth) guarda el ID de la categoría seleccionada en ese nivel.
     *
     * Ejemplo: si el usuario selecciona MOTOR y luego COCHE:
     *   $path = [1, 5]  →  depth 0: MOTOR (id=1), depth 1: COCHE (id=5)
     *
     * El atributo #[Url(as: 'path')] sincroniza esta propiedad con la URL del navegador.
     * Esto significa que:
     * - Al seleccionar categorías, la URL cambia: /products?path[]=1&path[]=5
     * - Al entrar directamente con esa URL, el filtro ya aparece aplicado
     * - El botón "atrás" del navegador restaura el filtro anterior
     */
    #[Url(as: 'path')]
    public array $path = [];

    /**
     * Se ejecuta automáticamente cuando el componente se inicializa (antes del primer render).
     *
     * PROBLEMA que resuelve: cuando $path se llena desde la URL (?path[]=1&path[]=5),
     * los valores llegan como strings ("1", "5") en lugar de integers (1, 5).
     * Esto rompía la comparación estricta === en la vista al resaltar la categoría activa.
     *
     * SOLUCIÓN: convertimos todos los valores del array a enteros con array_map('intval', ...).
     * Así "1" se convierte en 1 y la comparación $path[$depth] === $cat->id funciona.
     */
    public function mount(): void
    {
        $this->path = array_map('intval', $this->path);
    }

    /**
     * Selecciona una categoría en el nivel indicado y limpia los niveles más profundos.
     *
     * Si el usuario está en MOTOR > COCHE y hace click en MOTO (depth 1):
     * - array_slice($this->path, 0, 1) → conserva solo [MOTOR]
     * - Luego añade MOTO → [MOTOR, MOTO]
     * - Los niveles más profundos que había después de COCHE desaparecen
     *
     * @param int $depth Nivel de profundidad (0 = raíz, 1 = hijo, 2 = nieto...)
     * @param int $id    ID de la categoría seleccionada
     */
    public function selectLevel(int $depth, int $id): void
    {
        $this->path = array_slice($this->path, 0, $depth); // Conserva niveles anteriores
        $this->path[] = $id;                               // Añade el nuevo seleccionado
    }

    /**
     * Limpia la selección desde el nivel indicado en adelante.
     * Se usa en el botón "Todas/Todos" de cada fila de filtros.
     *
     * Ejemplo: clearFrom(1) sobre [MOTOR, COCHE] → [MOTOR]
     * Ejemplo: clearFrom(0) → [] (sin filtro, muestra todos los productos)
     *
     * @param int $depth Nivel desde el que se limpia (inclusive)
     */
    public function clearFrom(int $depth): void
    {
        $this->path = array_slice($this->path, 0, $depth);
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

        // Se hace un JOIN con categories para poder ordenar por categoría
        // Se carga la relación category con todos sus padres (para mostrar la categoría raíz)
        $query = Product::with(['images', 'category.parent.parent.parent.parent'])
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->select('products.*'); // Sin esto, el JOIN podría sobreescribir el id del producto

        if (!empty($filterIds)) {
            $query->whereIn('products.category_id', $filterIds);
        }

        // Ordenamos primero por categoría raíz y luego por nombre del producto
        $products = $query
            ->get()
            ->sortBy(fn ($p) => ($p->category?->root->name ?? '') . '|' . $p->nombre)
            ->values(); // values() re-indexa el array tras el sortBy

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
