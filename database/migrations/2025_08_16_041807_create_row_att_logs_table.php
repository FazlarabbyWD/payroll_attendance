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
        Schema::create('raw_attendance_log', function (Blueprint $table) {
            $table->id();
            $table->integer('uid')->comment("'uid' from device");
            $table->string('device_user_id', 50)->comment("'id' from device");
            $table->integer('state')->comment("'state' from device");
            $table->dateTime('attendance_time')->comment("'timestamp' from device");
            $table->integer('type')->default(0)->comment("'type' from device");
            $table->string('device_ip', 50)->comment("Device IP address");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('row_att_logs');
    }
};
