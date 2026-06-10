<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'HR Payroll System API is running',
        'version' => '1.0.0',
    ]);
});

Route::prefix('v1')->group(function () {
    // Auth routes (Issue #5)
    require __DIR__.'/api/v1/auth.php';

    // Protected routes
    Route::middleware(['auth:sanctum'])->group(function () {
        // Employee routes (Phase 2)
        require __DIR__.'/api/v1/employee.php';

        // Payroll routes (Phase 5)
        require __DIR__.'/api/v1/payroll.php';
    });
});