<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'HR Payroll System API is running',
        'version' => '1.0.0',
    ]);
});

// Auth routes (Phase 2)
// Employee routes (Phase 2)
// Payroll routes (Phase 5)