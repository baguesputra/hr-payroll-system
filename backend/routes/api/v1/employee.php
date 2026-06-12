<?php

use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\PositionController;
use Illuminate\Support\Facades\Route;

// Departments
Route::apiResource('departments', DepartmentController::class);

// Positions
Route::apiResource('positions', PositionController::class);

// Employees
Route::apiResource('employees', EmployeeController::class);
Route::post('employees/{employee}/terminate', [EmployeeController::class, 'terminate']);