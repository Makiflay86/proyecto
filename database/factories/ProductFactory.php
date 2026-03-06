<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para generar productos falsos en tests y seeders.
 *
 * Uso básico:
 *   Product::factory()->create()           → crea 1 producto activo en la BD
 *   Product::factory()->count(5)->create() → crea 5 productos
 *   Product::factory()->inactivo()->create() → crea 1 producto inactivo
 *   Product::factory()->make()             → crea el objeto sin guardarlo en BD
 */
class ProductFactory extends Factory
{
    /**
     * Define los valores por defecto de cada campo al generar un producto.
     * fake() genera datos aleatorios realistas (nombres, textos, números...)
     */
    public function definition(): array
    {
        return [
            'nombre'      => fake()->words(3, true),
            'descripcion' => fake()->paragraph(),
            'precio'      => fake()->randomFloat(2, 1, 1000), // número con 2 decimales entre 1 y 1000
            'categoria'   => fake()->randomElement(['electronica', 'ropa', 'hogar', 'alimentos']),
            'estado'      => 'activo', // por defecto todos los productos se crean activos
        ];
    }

    /**
     * Estado alternativo para crear un producto inactivo.
     * Uso: Product::factory()->inactivo()->create()
     */
    public function inactivo(): static
    {
        return $this->state(['estado' => 'inactivo']);
    }
}
