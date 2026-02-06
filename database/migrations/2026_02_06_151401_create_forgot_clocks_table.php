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
        Schema::create('forgot_clocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('clock_type', ['in', 'out'])->default('in'); // Jam Masuk (in) or Pulang (out)
            $table->time('clock_time'); // The time they forgot to clock
            $table->text('reason'); // Alasan
            
            // Approval Statuses
            $table->boolean('approved_by_supervisor')->default(false); 
            $table->boolean('acknowledged_by_hcs')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forgot_clocks');
    }
};
