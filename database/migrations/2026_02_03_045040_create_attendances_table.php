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
            $table->string('attendance_number', 50)->unique();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('attendance_date');
            
            // Check In
            $table->time('check_in_time')->nullable();
            $table->decimal('check_in_latitude', 10, 8)->nullable();
            $table->decimal('check_in_longitude', 11, 8)->nullable();
            $table->string('check_in_address')->nullable();
            $table->string('check_in_photo')->nullable();
            $table->string('check_in_city', 100)->nullable();
            $table->boolean('check_in_at_distributor')->default(false);
            
            // Check Out
            $table->time('check_out_time')->nullable();
            $table->decimal('check_out_latitude', 10, 8)->nullable();
            $table->decimal('check_out_longitude', 11, 8)->nullable();
            $table->string('check_out_address')->nullable();
            $table->string('check_out_photo')->nullable();
            $table->string('check_out_city', 100)->nullable();
            $table->boolean('check_out_at_distributor')->default(false);
            
            // Status
            $table->enum('status', ['present', 'absent', 'leave', 'sick', 'permission'])->default('present');
            $table->enum('check_in_status', ['ontime', 'late', 'absent'])->nullable();
            $table->enum('work_type', ['wfo', 'wfh', 'outside'])->default('wfo');
            
            // Approval
            $table->foreignId('approved_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('attendance_number');
            $table->index('employee_id');
            $table->index('attendance_date');
            $table->index('status');
            $table->index(['employee_id', 'attendance_date']);
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
