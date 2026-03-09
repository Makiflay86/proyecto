<?php

namespace App\Livewire;

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
        // ── Query 1: stats en una sola consulta ──────────────────────────────
        $stats = DB::selectOne('
            SELECT
                COUNT(*)                                             AS total,
                SUM(CASE WHEN estado = ? THEN 1 ELSE 0 END)        AS active,
                COUNT(DISTINCT category_id)                          AS categories
            FROM products
        ', ['activo']);

        $totalProducts    = (int) $stats->total;
        $activeProducts   = (int) $stats->active;
        $inactiveProducts = $totalProducts - $activeProducts;
        $categoriesCount  = (int) $stats->categories;

        // ── Query 2: últimos 5 productos ──────────────────────────────────────
        $recentProducts = Product::with('category')
            ->select('id', 'nombre', 'category_id', 'precio', 'estado', 'created_at')
            ->latest()
            ->take(5)
            ->get();

        // ── Query 3: agrupación por categoría (para los gráficos) ─────────────
        $productsByCategory = Product::selectRaw('category_id, COUNT(*) as total')
            ->with('category')
            ->groupBy('category_id')
            ->get()
            ->pluck('total', 'category.name');

        // ── Query 4: mensaje del día ───────────────────────────────────────────
        $dailyMessage = DailyMessage::whereDate('date', today())->first();

        return view('livewire.dashboard-content', [
            'message'            => $dailyMessage?->message,
            'totalProducts'      => $totalProducts,
            'activeProducts'     => $activeProducts,
            'inactiveProducts'   => $inactiveProducts,
            'categoriesCount'    => $categoriesCount,
            'recentProducts'     => $recentProducts,
            'productsByCategory' => $productsByCategory,
        ]);
    }
}
