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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->string('claim_number', 50)->unique();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            
            // Claim Details
            $table->string('claim_type', 100); // Medical, Transportation, etc.
            $table->string('claim_group', 100)->nullable();
            $table->date('claim_date');
            $table->decimal('amount', 15, 2);
            $table->text('description');
            
            // Attachments
            $table->json('attachments')->nullable(); // Store multiple file paths
            
            // Plafon Tracking
            $table->decimal('monthly_plafon', 15, 2)->nullable();
            $table->decimal('used_amount', 15, 2)->default(0);
            $table->decimal('remaining_plafon', 15, 2)->nullable();
            
            // Approval Chain
            $table->foreignId('supervisor_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('hcs_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('finance_id')->nullable()->constrained('employees')->nullOnDelete(); // FAT
            
            // Approval Status
            $table->enum('supervisor_status', ['pending', 'approved', 'rejected'])->nullable();
            $table->enum('hcs_status', ['pending', 'approved', 'rejected'])->nullable();
            $table->enum('finance_status', ['pending', 'approved', 'rejected'])->nullable();
            
            // Rejection Notes
            $table->text('supervisor_notes')->nullable();
            $table->text('hcs_notes')->nullable();
            $table->text('finance_notes')->nullable();
            
            // Approval Timestamps
            $table->timestamp('supervisor_approved_at')->nullable();
            $table->timestamp('hcs_approved_at')->nullable();
            $table->timestamp('finance_approved_at')->nullable();
            
            // Overall Status
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'paid'])->default('pending');
            
            // Payment
            $table->date('payment_date')->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_reference', 100)->nullable();
            
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('claim_number');
            $table->index('employee_id');
            $table->index('claim_type');
            $table->index('status');
            $table->index('claim_date');
            $table->index(['employee_id', 'claim_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
