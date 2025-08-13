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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->unique();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone_no')->nullable()->unique();
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->date('resigned_at')->nullable();

            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('designation_id')->nullable()->constrained()->onDelete('set null');

            $table->foreignId('employment_status_id')->default(1)->constrained('employment_statuses');
            $table->foreignId('employment_type_id')->default(1)->constrained('employment_types');
            $table->foreignId('gender_id')->nullable()->constrained('genders');
            $table->foreignId('religion_id')->nullable()->constrained('religions');
            $table->foreignId('marital_status_id')->nullable()->constrained('marital_statuses');
            $table->foreignId('blood_group_id')->nullable()->constrained('blood_groups');

            $table->string('national_id')->nullable()->unique();
            $table->string('passport_number')->nullable()->unique();
            $table->integer('employee_device_uid')->nullable()->unique();

            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');

            $table->index(['employee_id', 'phone_no', 'first_name'], 'employee_search_index');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
