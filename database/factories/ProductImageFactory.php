<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para generar imágenes de producto falsas en tests.
 *
 * Uso básico:
 *   ProductImage::factory()->create()
 *     → crea una imagen y también crea automáticamente un producto para ella
 *
 *   ProductImage::factory()->create(['product_id' => $product->id])
 *     → crea una imagen asociada a un producto existente (lo más habitual en tests)
 *
 *   ProductImage::factory()->count(3)->create(['product_id' => $product->id])
 *     → crea 3 imágenes para el mismo producto
 */
class ProductImageFactory extends Factory
{
    /**
     * Define los valores por defecto de cada campo al generar una imagen.
     */
    public function definition(): array
    {
        return [
            // Si no se pasa product_id, crea un producto nuevo automáticamente
            'product_id' => Product::factory(),

            // Simula una ruta de archivo en el disco public (storage/app/public/productos/)
            'path' => 'productos/' . fake()->uuid() . '.jpg',
        ];
    }
}
