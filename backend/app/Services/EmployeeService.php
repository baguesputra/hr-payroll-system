<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeSalaryHistory;
use App\Models\EmployeePositionHistory;
use App\Models\Position;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    public function getAllEmployees(array $filters = []): LengthAwarePaginator
    {
        $query = Employee::query()
            ->with(['position', 'department', 'latestSalary'])
            ->when(isset($filters['search']), function ($q) use ($filters) {
                $q->where(function ($q) use ($filters) {
                    $q->where('full_name', 'ilike', "%{$filters['search']}%")
                      ->orWhere('nik', 'ilike', "%{$filters['search']}%")
                      ->orWhere('employee_number', 'ilike', "%{$filters['search']}%");
                });
            })
            ->when(isset($filters['department_id']), fn ($q) =>
                $q->where('department_id', $filters['department_id'])
            )
            ->when(isset($filters['employment_status']), fn ($q) =>
                $q->where('employment_status', $filters['employment_status'])
            )
            ->when(isset($filters['is_active']), fn ($q) =>
                $q->where('is_active', $filters['is_active'])
            )
            ->orderBy('full_name');

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function createEmployee(array $data, int $createdBy): Employee
    {
        return DB::transaction(function () use ($data, $createdBy) {
            $employee = Employee::create([
                'nik'               => $data['nik'],
                'employee_number'   => $data['employee_number'],
                'full_name'         => $data['full_name'],
                'gender'            => $data['gender'],
                'birth_date'        => $data['birth_date'] ?? null,
                'marital_status'    => $data['marital_status'],
                'ptkp_status'       => $data['ptkp_status'],
                'employment_status' => $data['employment_status'],
                'position_id'       => $data['position_id'],
                'department_id'     => $data['department_id'],
                'join_date'         => $data['join_date'],
                'phone'             => $data['phone'] ?? null,
                'address'           => $data['address'] ?? null,
                'npwp'              => $data['npwp'] ?? null,
                'bpjs_tk_number'    => $data['bpjs_tk_number'] ?? null,
                'bpjs_kes_number'   => $data['bpjs_kes_number'] ?? null,
                'bank_name'         => $data['bank_name'] ?? null,
                'bank_account'      => $data['bank_account'] ?? null,
            ]);

            // Catat salary history awal
            EmployeeSalaryHistory::create([
                'employee_id'    => $employee->id,
                'base_salary'    => $data['base_salary'],
                'effective_date' => $data['join_date'],
                'reason'         => 'Initial salary',
                'created_by'     => $createdBy,
            ]);

            // Catat position history awal
            EmployeePositionHistory::create([
                'employee_id'    => $employee->id,
                'position_id'    => $data['position_id'],
                'department_id'  => $data['department_id'],
                'effective_date' => $data['join_date'],
                'reason'         => 'Initial position',
                'created_by'     => $createdBy,
            ]);

            return $employee->load(['position', 'department', 'latestSalary']);
        });
    }

    public function updateEmployee(Employee $employee, array $data, int $updatedBy): Employee
    {
        return DB::transaction(function () use ($employee, $data, $updatedBy) {
            // Cek apakah position berubah
            $positionChanged = isset($data['position_id']) &&
                $data['position_id'] != $employee->position_id;

            $departmentChanged = isset($data['department_id']) &&
                $data['department_id'] != $employee->department_id;

            $employee->update($data);

            // Catat position history kalau berubah
            if ($positionChanged || $departmentChanged) {
                EmployeePositionHistory::create([
                    'employee_id'    => $employee->id,
                    'position_id'    => $employee->position_id,
                    'department_id'  => $employee->department_id,
                    'effective_date' => now()->toDateString(),
                    'reason'         => $data['reason'] ?? 'Position update',
                    'created_by'     => $updatedBy,
                ]);
            }

            return $employee->fresh(['position', 'department', 'latestSalary']);
        });
    }

    public function terminateEmployee(Employee $employee, string $resignDate): Employee
    {
        $employee->update([
            'resign_date' => $resignDate,
            'is_active'   => false,
        ]);

        return $employee->fresh();
    }

    public function getAllDepartments(): \Illuminate\Database\Eloquent\Collection
    {
        return Department::with('manager')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function getAllPositions(?int $departmentId = null): \Illuminate\Database\Eloquent\Collection
    {
        return Position::query()
            ->with('department')
            ->when($departmentId, fn ($q) => $q->where('department_id', $departmentId))
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function getSalaryHistories(Employee $employee): \Illuminate\Database\Eloquent\Collection
    {
        return $employee->salaryHistories()
            ->with('createdBy:id,name')
            ->orderByDesc('effective_date')
            ->get();
    }

    public function updateSalary(Employee $employee, array $data, int $updatedBy): EmployeeSalaryHistory
    {
        $history = EmployeeSalaryHistory::create([
            'employee_id'    => $employee->id,
            'base_salary'    => $data['base_salary'],
            'effective_date' => $data['effective_date'],
            'reason'         => $data['reason'],
            'created_by'     => $updatedBy,
        ]);

        return $history->load('createdBy:id,name');
    }

    public function getPositionHistories(Employee $employee): \Illuminate\Database\Eloquent\Collection
    {
        return $employee->positionHistories()
            ->with(['position:id,name', 'department:id,name', 'createdBy:id,name'])
            ->orderByDesc('effective_date')
            ->get();
    }

    public function updatePosition(Employee $employee, array $data, int $updatedBy): EmployeePositionHistory
    {
        return DB::transaction(function () use ($employee, $data, $updatedBy) {
            // Update posisi aktif karyawan
            $employee->update([
                'position_id'   => $data['position_id'],
                'department_id' => $data['department_id'],
            ]);

            // Catat history
            $history = EmployeePositionHistory::create([
                'employee_id'    => $employee->id,
                'position_id'    => $data['position_id'],
                'department_id'  => $data['department_id'],
                'effective_date' => $data['effective_date'],
                'reason'         => $data['reason'],
                'created_by'     => $updatedBy,
            ]);

            return $history->load(['position:id,name', 'department:id,name', 'createdBy:id,name']);
        });
    }
}