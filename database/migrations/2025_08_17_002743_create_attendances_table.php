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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->integer('total_minutes')->nullable();

            $table->time('late_by')->nullable();
            $table->time('early_leave_by')->nullable(); //
            $table->integer('overtime_minutes')->default(0);

            $table->enum('status', ['Present', 'Absent', 'Late', 'On Leave', 'Holiday'])->default('Present');
            $table->boolean('is_manual')->default(false);

            $table->timestamps();

            $table->unique(['employee_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
