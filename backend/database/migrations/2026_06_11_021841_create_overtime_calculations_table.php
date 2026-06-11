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
        Schema::create('overtime_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('overtime_request_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('regular_hours', 5, 2)->default(0);
            $table->decimal('one_point_five_hours', 5, 2)->default(0);
            $table->decimal('two_hours', 5, 2)->default(0);
            $table->decimal('hourly_rate', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtime_calculations');
    }
};
