<?php

use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\EmployeeHistoryController;
use App\Http\Controllers\Api\PositionController;
use Illuminate\Support\Facades\Route;

// Departments
Route::apiResource('departments', DepartmentController::class);

// Positions
Route::apiResource('positions', PositionController::class);

// Employees
Route::apiResource('employees', EmployeeController::class);
Route::post('employees/{employee}/terminate', [EmployeeController::class, 'terminate']);

// Employee histories
Route::prefix('employees/{employee}')->group(function () {
    Route::get('salary-histories', [EmployeeHistoryController::class, 'salaryHistories']);
    Route::post('salary-histories', [EmployeeHistoryController::class, 'updateSalary']);
    Route::get('position-histories', [EmployeeHistoryController::class, 'positionHistories']);
    Route::post('position-histories', [EmployeeHistoryController::class, 'updatePosition']);
});