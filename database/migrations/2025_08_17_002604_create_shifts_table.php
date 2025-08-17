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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "General Shift", "Night Shift"
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('grace_period')->default(0);       // late allowance (minutes)
            $table->integer('break_minutes')->default(0);      // unpaid break
            $table->boolean('is_night_shift')->default(false); // true if crosses midnight
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
