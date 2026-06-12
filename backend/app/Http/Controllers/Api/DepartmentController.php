<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\CreateDepartmentRequest;
use App\Models\Department;
use App\Services\EmployeeService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly EmployeeService $employeeService
    ) {}

    public function index(): JsonResponse
    {
        $departments = $this->employeeService->getAllDepartments();
        return $this->successResponse($departments);
    }

    public function store(CreateDepartmentRequest $request): JsonResponse
    {
        $department = Department::create($request->validated());
        return $this->createdResponse($department, 'Department created successfully');
    }

    public function show(Department $department): JsonResponse
    {
        $department->load(['manager', 'positions']);
        return $this->successResponse($department);
    }

    public function update(CreateDepartmentRequest $request, Department $department): JsonResponse
    {
        $department->update($request->validated());
        return $this->successResponse($department, 'Department updated successfully');
    }

    public function destroy(Department $department): JsonResponse
    {
        $department->delete();
        return $this->successResponse(message: 'Department deleted successfully');
    }
}