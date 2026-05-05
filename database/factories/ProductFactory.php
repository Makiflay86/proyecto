<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'category_id' => Category::factory(),
            'nombre'      => fake()->words(3, true),
            'descripcion' => fake()->paragraph(),
            'precio'      => fake()->randomFloat(2, 1, 1000),
            'estado'      => 'activo',
        ];
    }

    public function inactivo(): static
    {
        return $this->state(['estado' => 'inactivo']);
    }

    public function vendido(): static
    {
        return $this->state(['estado' => 'vendido']);
    }

    public function reservado(): static
    {
        return $this->state(['estado' => 'reservado']);
    }
}
