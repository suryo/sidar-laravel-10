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
        Schema::create('overtime_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('overtime_mandate_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade'); // Nama (One or more)
            $table->time('start_time'); // Realisasi Waktu From
            $table->time('end_time'); // Realisasi Waktu To
            $table->integer('duration_minutes')->nullable(); // Total Waktu Lembur (minutes)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtime_employees');
    }
};
