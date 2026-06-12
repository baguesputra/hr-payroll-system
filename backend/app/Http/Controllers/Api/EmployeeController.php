<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\CreateEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Services\EmployeeService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly EmployeeService $employeeService
    ) {}

    public function index(Request $request): JsonResponse
{
    if (! $request->user()->can('employee.view')) {
        return $this->forbiddenResponse();
    }

    $employees = $this->employeeService->getAllEmployees(
        $request->only(['search', 'department_id', 'employment_status', 'is_active', 'per_page'])
    );

        return $this->successResponse([
            'items' => $employees->items(),
            'meta'  => [
                'current_page' => $employees->currentPage(),
                'last_page'    => $employees->lastPage(),
                'per_page'     => $employees->perPage(),
                'total'        => $employees->total(),
                'from'         => $employees->firstItem(),
                'to'           => $employees->lastItem(),
            ],
        ]);
    }

    public function store(CreateEmployeeRequest $request): JsonResponse
    {
        $employee = $this->employeeService->createEmployee(
            $request->validated(),
            $request->user()->id
        );

        return $this->createdResponse($employee, 'Employee created successfully');
    }

    public function show(Employee $employee, Request $request): JsonResponse
{
    if (! $request->user()->can('employee.view')) {
        return $this->forbiddenResponse();
    }

        $employee->load(['position', 'department', 'latestSalary', 'user']);

        return $this->successResponse($employee);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): JsonResponse
    {
        $updated = $this->employeeService->updateEmployee(
            $employee,
            $request->validated(),
            $request->user()->id
        );

        return $this->successResponse($updated, 'Employee updated successfully');
    }

    public function destroy(Employee $employee, Request $request): JsonResponse
{
    if (! $request->user()->can('employee.delete')) {
        return $this->forbiddenResponse();
    }

        $employee->delete();

        return $this->successResponse(message: 'Employee deleted successfully');
    }

    public function terminate(Request $request, Employee $employee): JsonResponse
{
    if (! $request->user()->can('employee.update')) {
        return $this->forbiddenResponse();
    }

    $request->validate([
        'resign_date' => ['required', 'date'],
    ]);

        $updated = $this->employeeService->terminateEmployee(
            $employee,
            $request->resign_date
        );

        return $this->successResponse($updated, 'Employee terminated successfully');
    }
}