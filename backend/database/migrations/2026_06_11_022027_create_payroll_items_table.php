<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->restrictOnDelete();

            // Snapshot data karyawan saat payroll diproses
            $table->string('employee_name');
            $table->string('employee_number');
            $table->string('position_name');
            $table->string('department_name');
            $table->enum('ptkp_status', ['TK/0', 'K/0', 'K/1', 'K/2', 'K/3']);

            // Kehadiran
            $table->integer('working_days')->default(0);
            $table->integer('actual_working_days')->default(0);
            $table->integer('absent_days')->default(0);
            $table->decimal('prorate_factor', 5, 4)->default(1.0000);

            // Komponen gaji (JSONB untuk fleksibilitas)
            $table->decimal('base_salary', 15, 2)->default(0);
            $table->jsonb('earnings')->default('{}');
            $table->jsonb('deductions')->default('{}');

            // Summary
            $table->decimal('total_earnings', 15, 2)->default(0);
            $table->decimal('total_deductions', 15, 2)->default(0);
            $table->decimal('gross_salary', 15, 2)->default(0);

            // BPJS
            $table->decimal('bpjs_tk_employee', 15, 2)->default(0);
            $table->decimal('bpjs_tk_employer', 15, 2)->default(0);
            $table->decimal('bpjs_kes_employee', 15, 2)->default(0);
            $table->decimal('bpjs_kes_employer', 15, 2)->default(0);

            // Pajak
            $table->decimal('pph21_amount', 15, 2)->default(0);

            // Tambahan
            $table->decimal('overtime_amount', 15, 2)->default(0);
            $table->decimal('leave_encashment_amount', 15, 2)->default(0);

            // Final
            $table->decimal('nett_salary', 15, 2)->default(0);

            $table->enum('status', ['draft', 'processed', 'approved', 'locked'])->default('draft');
            $table->timestamps();

            $table->unique(['payroll_period_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
    }
};
