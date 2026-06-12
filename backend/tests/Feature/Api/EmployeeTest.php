<?php

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    $this->hrManager = User::factory()->create(['is_active' => true]);
    $this->hrManager->assignRole('hr_manager');
});

it('allows hr manager to list employees', function () {
    Employee::factory(3)->create();

    $response = $this->actingAs($this->hrManager)
        ->getJson('/api/v1/employees');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'items',
                'meta' => ['current_page', 'last_page', 'per_page', 'total'],
            ],
        ]);
});

it('allows hr manager to create employee', function () {
    $department = Department::factory()->create();
    $position   = Position::factory()->create(['department_id' => $department->id]);

    $response = $this->actingAs($this->hrManager)
        ->postJson('/api/v1/employees', [
            'nik'               => '1234567890123456',
            'employee_number'   => 'EMP-0001',
            'full_name'         => 'John Doe',
            'gender'            => 'male',
            'marital_status'    => 'single',
            'ptkp_status'       => 'TK/0',
            'employment_status' => 'permanent',
            'position_id'       => $position->id,
            'department_id'     => $department->id,
            'join_date'         => '2024-01-01',
            'base_salary'       => 5000000,
        ]);

    $response->assertCreated()
        ->assertJsonPath('data.full_name', 'John Doe');

    $this->assertDatabaseHas('employees', ['nik' => '1234567890123456']);
    $this->assertDatabaseHas('employee_salary_histories', ['base_salary' => 5000000]);
    $this->assertDatabaseHas('employee_position_histories', ['position_id' => $position->id]);
});

it('prevents duplicate NIK', function () {
    $department = Department::factory()->create();
    $position   = Position::factory()->create(['department_id' => $department->id]);
    Employee::factory()->create(['nik' => '1234567890123456']);

    $response = $this->actingAs($this->hrManager)
        ->postJson('/api/v1/employees', [
            'nik'               => '1234567890123456',
            'employee_number'   => 'EMP-0002',
            'full_name'         => 'Jane Doe',
            'gender'            => 'female',
            'marital_status'    => 'single',
            'ptkp_status'       => 'TK/0',
            'employment_status' => 'permanent',
            'position_id'       => $position->id,
            'department_id'     => $department->id,
            'join_date'         => '2024-01-01',
            'base_salary'       => 5000000,
        ]);

    $response->assertUnprocessable();
});

it('prevents employee role from creating employee', function () {
    $employee = User::factory()->create(['is_active' => true]);
    $employee->assignRole('employee');

    $response = $this->actingAs($employee)
        ->postJson('/api/v1/employees', []);

    $response->assertForbidden();
});

it('allows hr manager to update employee', function () {
    $emp = Employee::factory()->create();

    $response = $this->actingAs($this->hrManager)
        ->putJson("/api/v1/employees/{$emp->id}", [
            'full_name' => 'Updated Name',
        ]);

    $response->assertOk()
        ->assertJsonPath('data.full_name', 'Updated Name');
});

it('allows hr manager to terminate employee', function () {
    $emp = Employee::factory()->create(['is_active' => true]);

    $response = $this->actingAs($this->hrManager)
        ->postJson("/api/v1/employees/{$emp->id}/terminate", [
            'resign_date' => '2024-12-31',
        ]);

    $response->assertOk();
    $this->assertDatabaseHas('employees', [
        'id'        => $emp->id,
        'is_active' => false,
    ]);
});