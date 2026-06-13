<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeSalaryHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'employee_id'    => Employee::factory(),
            'base_salary'    => fake()->numberBetween(3000000, 20000000),
            'effective_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'reason'         => fake()->randomElement(['Initial salary', 'Annual review', 'Promotion']),
            'created_by'     => User::factory(),
        ];
    }
}