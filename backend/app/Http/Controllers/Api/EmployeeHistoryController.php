<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\UpdatePositionRequest;
use App\Http\Requests\Employee\UpdateSalaryRequest;
use App\Models\Employee;
use App\Services\EmployeeService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeHistoryController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly EmployeeService $employeeService
    ) {}

    public function salaryHistories(Request $request, Employee $employee): JsonResponse
    {
        if (! $request->user()->can('employee.view')) {
            return $this->forbiddenResponse();
        }

        $histories = $this->employeeService->getSalaryHistories($employee);

        return $this->successResponse($histories);
    }

    public function updateSalary(UpdateSalaryRequest $request, Employee $employee): JsonResponse
    {
        $history = $this->employeeService->updateSalary(
            $employee,
            $request->validated(),
            $request->user()->id
        );

        return $this->createdResponse($history, 'Salary updated successfully');
    }

    public function positionHistories(Request $request, Employee $employee): JsonResponse
    {
        if (! $request->user()->can('employee.view')) {
            return $this->forbiddenResponse();
        }

        $histories = $this->employeeService->getPositionHistories($employee);

        return $this->successResponse($histories);
    }

    public function updatePosition(UpdatePositionRequest $request, Employee $employee): JsonResponse
    {
        $history = $this->employeeService->updatePosition(
            $employee,
            $request->validated(),
            $request->user()->id
        );

        return $this->createdResponse($history, 'Position updated successfully');
    }
}