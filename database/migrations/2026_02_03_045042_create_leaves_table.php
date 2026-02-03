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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->string('leave_number', 50)->unique();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            
            // Leave Details
            $table->enum('type', ['annual', 'sick', 'permission', 'late', 'other'])->default('annual');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days')->default(1);
            $table->text('reason');
            
            // For Late Permission
            $table->time('late_arrival_time')->nullable();
            
            // Delegation (for annual leave)
            $table->foreignId('delegate_to')->nullable()->constrained('employees')->nullOnDelete();
            $table->text('delegation_tasks')->nullable();
            $table->enum('delegate_status', ['pending', 'approved', 'rejected'])->nullable();
            $table->timestamp('delegate_approved_at')->nullable();
            
            // Approval Chain
            $table->foreignId('supervisor_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('hcs_id')->nullable()->constrained('employees')->nullOnDelete(); // HCS = Human Capital Service
            
            // Approval Status
            $table->enum('supervisor_status', ['pending', 'approved', 'rejected'])->nullable();
            $table->enum('hcs_status', ['pending', 'approved', 'rejected'])->nullable();
            $table->text('supervisor_notes')->nullable();
            $table->text('hcs_notes')->nullable();
            
            // Approval Timestamps
            $table->timestamp('supervisor_approved_at')->nullable();
            $table->timestamp('hcs_approved_at')->nullable();
            
            // Overall Status
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('leave_number');
            $table->index('employee_id');
            $table->index('type');
            $table->index('status');
            $table->index('start_date');
            $table->index(['employee_id', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
