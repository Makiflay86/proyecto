<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class Charts extends Component
{
    public function render()
    {
        $rootCategories = Category::whereNull('parent_id')->orderBy('name')->get();

        $allCats = Category::all(['id', 'parent_id', 'name'])->keyBy('id');
        $rootOf = [];
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

        $statsByCategory = [];
        foreach ($rootCategories as $root) {
            $statsByCategory[$root->name] = [
                'total' => 0,
                'activo' => 0,
                'reservado' => 0,
                'vendido' => 0,
                'inactivo' => 0,
            ];
        }

        $productCounts = Product::selectRaw('category_id, estado, COUNT(*) as total')
            ->groupBy('category_id', 'estado')
            ->get();

        foreach ($productCounts as $row) {
            $rootName = $rootOf[$row->category_id] ?? null;
            if ($rootName && isset($statsByCategory[$rootName])) {
                $statsByCategory[$rootName]['total'] += $row->total;
                $statsByCategory[$rootName][$row->estado] = ($statsByCategory[$rootName][$row->estado] ?? 0) + $row->total;
            }
        }

        return view('livewire.admin.charts', [
            'chartLabels' => $productsByCategory->keys(),
            'chartValues' => $productsByCategory->values(),
            'statsByCategory' => collect($statsByCategory)->sortByDesc('total'),
        ])->layout('layouts.app');
    }
}
