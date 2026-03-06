<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre'      => fake()->words(3, true),
            'descripcion' => fake()->paragraph(),
            'precio'      => fake()->randomFloat(2, 1, 1000),
            'categoria'   => fake()->randomElement(['electronica', 'ropa', 'hogar', 'alimentos']),
            'estado'      => 'activo',
        ];
    }

    public function inactivo(): static
    {
        return $this->state(['estado' => 'inactivo']);
    }
}
