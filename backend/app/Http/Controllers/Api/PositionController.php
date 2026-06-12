<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\CreatePositionRequest;
use App\Models\Position;
use App\Services\EmployeeService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly EmployeeService $employeeService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $positions = $this->employeeService->getAllPositions(
            $request->integer('department_id') ?: null
        );
        return $this->successResponse($positions);
    }

    public function store(CreatePositionRequest $request): JsonResponse
    {
        $position = Position::create($request->validated());
        return $this->createdResponse(
            $position->load('department'),
            'Position created successfully'
        );
    }

    public function show(Position $position): JsonResponse
    {
        return $this->successResponse($position->load('department'));
    }

    public function update(CreatePositionRequest $request, Position $position): JsonResponse
    {
        $position->update($request->validated());
        return $this->successResponse($position, 'Position updated successfully');
    }

    public function destroy(Position $position): JsonResponse
    {
        $position->delete();
        return $this->successResponse(message: 'Position deleted successfully');
    }
}