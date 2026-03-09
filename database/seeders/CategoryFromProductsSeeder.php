<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder de migración de datos: coge los valores únicos del campo `categoria`
 * de la tabla products e inserta cada uno como registro en la tabla categories.
 *
 * Es seguro ejecutarlo varias veces: usa insertOrIgnore para no duplicar
 * categorías que ya existan en la tabla.
 *
 * Uso: php artisan db:seed --class=CategoryFromProductsSeeder
 */
class CategoryFromProductsSeeder extends Seeder
{
    public function run(): void
    {
        // Obtiene los valores únicos y no nulos del campo categoria de products
        $categorias = DB::table('products')
            ->select('categoria')
            ->whereNotNull('categoria')
            ->where('categoria', '!=', '')
            ->distinct()
            ->pluck('categoria');

        $now = now();

        // insertOrIgnore: si el nombre ya existe (unique) lo salta sin error
        foreach ($categorias as $nombre) {
            Category::firstOrCreate(
                ['name' => $nombre],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }

        $this->command->info("Se importaron {$categorias->count()} categorías desde productos.");
    }
}
