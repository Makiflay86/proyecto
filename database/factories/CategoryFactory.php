<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'      => fake()->unique()->words(2, true),
            'parent_id' => null,
            'image'     => null,
        ];
    }

    public function child(int $parentId): static
    {
        return $this->state(['parent_id' => $parentId]);
    }
}
