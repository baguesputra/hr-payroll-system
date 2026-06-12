<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'      => fake()->unique()->words(2, true),
            'code'      => fake()->unique()->lexify('DEPT-??'),
            'is_active' => true,
        ];
    }
}