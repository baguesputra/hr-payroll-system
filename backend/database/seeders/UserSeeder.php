<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@hrpayroll.test'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // HR Manager
        $hrManager = User::firstOrCreate(
            ['email' => 'hr@hrpayroll.test'],
            [
                'name'     => 'HR Manager',
                'password' => Hash::make('password'),
            ]
        );
        $hrManager->assignRole('hr_manager');

        // Finance
        $finance = User::firstOrCreate(
            ['email' => 'finance@hrpayroll.test'],
            [
                'name'     => 'Finance',
                'password' => Hash::make('password'),
            ]
        );
        $finance->assignRole('finance');

        // Employee
        $employee = User::firstOrCreate(
            ['email' => 'employee@hrpayroll.test'],
            [
                'name'     => 'John Employee',
                'password' => Hash::make('password'),
            ]
        );
        $employee->assignRole('employee');
    }
}