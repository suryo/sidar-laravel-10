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
        Schema::create('dars', function (Blueprint $table) {
            $table->id();
            $table->string('dar_number', 50)->unique();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('dar_date');
            
            // Content
            $table->text('activity');
            $table->text('result');
            $table->text('plan');
            $table->string('tag')->nullable(); // For attendance reference
            
            // Status & Approval
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('pending');
            $table->enum('submission_status', ['ontime', 'late', 'over'])->default('ontime');
            $table->boolean('is_read')->default(false);
            
            // Approval Chain
            $table->foreignId('supervisor_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('manager_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('senior_manager_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('director_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('employees')->nullOnDelete();
            
            // Approval Status
            $table->enum('supervisor_status', ['pending', 'approved', 'rejected'])->nullable();
            $table->enum('manager_status', ['pending', 'approved', 'rejected'])->nullable();
            $table->enum('senior_manager_status', ['pending', 'approved', 'rejected'])->nullable();
            $table->enum('director_status', ['pending', 'approved', 'rejected'])->nullable();
            $table->enum('owner_status', ['pending', 'approved', 'rejected'])->nullable();
            
            // Approval Timestamps
            $table->timestamp('supervisor_approved_at')->nullable();
            $table->timestamp('manager_approved_at')->nullable();
            $table->timestamp('senior_manager_approved_at')->nullable();
            $table->timestamp('director_approved_at')->nullable();
            $table->timestamp('owner_approved_at')->nullable();
            
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('dar_number');
            $table->index('employee_id');
            $table->index('dar_date');
            $table->index('status');
            $table->index(['employee_id', 'dar_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dars');
    }
};
