<?php

namespace App\Livewire\Admin;

use App\Models\DailyMessage;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

/**
 * Componente Livewire que contiene todo el contenido dinámico del dashboard.
 *
 * El auto-refresco cada 30 s se controla desde la vista mediante wire:poll.30000ms
 * en el div raíz del blade (Livewire v4 no usa #[Poll] como atributo PHP).
 *
 * Los gráficos Chart.js no se re-renderizan en cada poll:
 *   - Sus contenedores tienen wire:ignore → Livewire no toca los canvas.
 *   - dashboard.js observa #chart-data con MutationObserver y actualiza los
 *     datos de los gráficos cuando Livewire cambia los data-attributes.
 */
class DashboardContent extends Component
{
    public function render()
    {
        $stats = DB::selectOne('
            SELECT
                COUNT(*)                                              AS total,
                SUM(CASE WHEN estado = "activo" THEN 1 ELSE 0 END)    AS active,
                SUM(CASE WHEN estado = "reservado" THEN 1 ELSE 0 END) AS reserved,
                SUM(CASE WHEN estado = "vendido" THEN 1 ELSE 0 END)   AS sold,
                SUM(CASE WHEN estado = "inactivo" THEN 1 ELSE 0 END)  AS inactive,
                COUNT(DISTINCT category_id)                           AS categories
            FROM products
        ');

        $totalProducts    = (int) $stats->total;
        $activeProducts   = (int) $stats->active;
        $reservedProducts = (int) $stats->reserved;
        $soldProducts     = (int) $stats->sold;
        $inactiveProducts = (int) $stats->inactive;
        $categoriesCount  = (int) $stats->categories;

        $recentProducts = Product::with('category.parent.parent.parent.parent')
            ->select('id', 'nombre', 'category_id', 'precio', 'estado', 'created_at')
            ->latest()
            ->take(5)
            ->get();

        $allCats = \App\Models\Category::all(['id', 'parent_id', 'name'])->keyBy('id');
        $rootOf  = [];
        foreach ($allCats as $cat) {
            $current = $cat;
            while ($current->parent_id !== null && isset($allCats[$current->parent_id])) {
                $current = $allCats[$current->parent_id];
            }
            $rootOf[$cat->id] = $current->name;
        }

        $productsByCategory = Product::selectRaw('category_id, COUNT(*) as total')
            ->groupBy('category_id')
            ->get()
            ->groupBy(fn ($row) => $rootOf[$row->category_id] ?? '—')
            ->map(fn ($group) => $group->sum('total'))
            ->sortKeys();

        $dailyMessage = DailyMessage::whereDate('date', today())->first();

        if (!$dailyMessage) {
            \Illuminate\Support\Facades\Artisan::call('app:generate-daily-message');
            $dailyMessage = DailyMessage::whereDate('date', today())->first();
        }

        return view('livewire.admin.dashboard-content', [
            'message'            => $dailyMessage?->message,
            'totalProducts'      => $totalProducts,
            'activeProducts'     => $activeProducts,
            'reservedProducts'   => $reservedProducts,
            'soldProducts'       => $soldProducts,
            'inactiveProducts'   => $inactiveProducts,
            'categoriesCount'    => $categoriesCount,
            'recentProducts'     => $recentProducts,
            'productsByCategory' => $productsByCategory,
        ]);
    }
}
