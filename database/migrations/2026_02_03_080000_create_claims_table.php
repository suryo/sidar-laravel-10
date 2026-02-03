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
        Schema::dropIfExists('claims');
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->string('claim_number')->unique(); // CLM-NIK-YYYYMMDD-XXX
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            
            // Claim Details
            $table->enum('type', ['medical', 'optical', 'transport', 'travel', 'other']);
            $table->decimal('amount', 12, 2);
            $table->text('description');
            $table->string('proof_file')->nullable(); // Path to receipt image/pdf
            
            // Status & Approvals
            $table->enum('status', ['pending', 'approved_supervisor', 'approved_hcs', 'paid', 'rejected'])->default('pending');
            
            // Supervisor Approval
            $table->foreignId('supervisor_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->timestamp('supervisor_approved_at')->nullable();
            $table->text('supervisor_notes')->nullable();
            
            // HCS Approval
            $table->foreignId('hcs_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->timestamp('hcs_approved_at')->nullable();
            $table->text('hcs_notes')->nullable();
            
            // Finance Processing (Payment)
            $table->foreignId('finance_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->timestamp('finance_processed_at')->nullable();
            $table->text('finance_notes')->nullable();
            
            // Rejection
            $table->text('rejection_note')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('employees')->nullOnDelete();

            $table->timestamps();
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
