<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('late_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('late_date');
            $table->time('late_duration')->nullable(); // e.g. 00:15:00
            $table->time('arrival_time')->nullable(); // Actual arrival time
            $table->text('reason');
            
            // Approval Statuses
            $table->boolean('approved_by_supervisor')->default(false); // Can be null if pending? Or simple boolean as in screenshot "Setuju" vs "Belum"
            $table->boolean('acknowledged_by_hcs')->default(false);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('late_permissions');
    }
};
