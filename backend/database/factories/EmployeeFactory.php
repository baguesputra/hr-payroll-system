<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'department_id'     => Department::factory(),
            'position_id'       => Position::factory(),
            'nik'               => fake()->unique()->numerify('################'),
            'employee_number'   => fake()->unique()->numerify('EMP-####'),
            'full_name'         => fake()->name(),
            'gender'            => fake()->randomElement(['male', 'female']),
            'birth_date'        => fake()->dateTimeBetween('-50 years', '-20 years'),
            'marital_status'    => fake()->randomElement(['single', 'married']),
            'ptkp_status'       => fake()->randomElement(['TK/0', 'K/0', 'K/1']),
            'employment_status' => fake()->randomElement(['permanent', 'contract', 'probation']),
            'join_date'         => fake()->dateTimeBetween('-5 years', 'now'),
            'is_active'         => true,
        ];
    }
}