<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'department_id' => Department::factory(),
            'name'          => fake()->unique()->jobTitle(),
            'code'          => fake()->unique()->lexify('POS-??'),
            'level'         => fake()->numberBetween(1, 5),
            'is_active'     => true,
        ];
    }
}