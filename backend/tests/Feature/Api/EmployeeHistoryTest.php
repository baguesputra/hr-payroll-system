<?php

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeSalaryHistory;
use App\Models\Position;
use App\Models\User;

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    $this->hrManager = User::factory()->create(['is_active' => true]);
    $this->hrManager->assignRole('hr_manager');

    $department       = Department::factory()->create();
    $position         = Position::factory()->create(['department_id' => $department->id]);
    $this->employee   = Employee::factory()->create([
        'department_id' => $department->id,
        'position_id'   => $position->id,
    ]);
});

it('allows hr manager to view salary histories', function () {
    EmployeeSalaryHistory::factory()->create([
        'employee_id' => $this->employee->id,
        'created_by'  => $this->hrManager->id,
    ]);

    $response = $this->actingAs($this->hrManager)
        ->getJson("/api/v1/employees/{$this->employee->id}/salary-histories");

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'base_salary', 'effective_date', 'reason'],
            ],
        ]);
});

it('allows hr manager to update employee salary', function () {
    $response = $this->actingAs($this->hrManager)
        ->postJson("/api/v1/employees/{$this->employee->id}/salary-histories", [
            'base_salary'    => 7000000,
            'effective_date' => '2024-06-01',
            'reason'         => 'Annual review',
        ]);

    $response->assertCreated();

    $this->assertDatabaseHas('employee_salary_histories', [
        'employee_id' => $this->employee->id,
        'base_salary' => 7000000,
    ]);
});

it('allows hr manager to view position histories', function () {
    $response = $this->actingAs($this->hrManager)
        ->getJson("/api/v1/employees/{$this->employee->id}/position-histories");

    $response->assertOk();
});

it('allows hr manager to update employee position', function () {
    $newDepartment = Department::factory()->create();
    $newPosition   = Position::factory()->create(['department_id' => $newDepartment->id]);

    $response = $this->actingAs($this->hrManager)
        ->postJson("/api/v1/employees/{$this->employee->id}/position-histories", [
            'position_id'    => $newPosition->id,
            'department_id'  => $newDepartment->id,
            'effective_date' => '2024-06-01',
            'reason'         => 'Promotion',
        ]);

    $response->assertCreated();

    $this->assertDatabaseHas('employee_position_histories', [
        'employee_id' => $this->employee->id,
        'position_id' => $newPosition->id,
    ]);

    $this->assertDatabaseHas('employees', [
        'id'          => $this->employee->id,
        'position_id' => $newPosition->id,
    ]);
});