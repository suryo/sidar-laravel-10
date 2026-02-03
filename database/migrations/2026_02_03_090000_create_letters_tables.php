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
        // Templates Table
        Schema::dropIfExists('letter_templates');
        Schema::create('letter_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Perjanjian Kerja Waktu Tertentu"
            $table->string('code')->unique(); // e.g. "PKWT", "SP1"
            $table->longText('content'); // HTML content with placeholders like [NAME]
            $table->timestamps();
        });

        // Letters Table
        Schema::dropIfExists('letters');
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->string('letter_number')->nullable()->unique(); // Generated on approval
            $table->foreignId('template_id')->constrained('letter_templates');
            $table->foreignId('creator_id')->constrained('employees'); // Who drafted it
            
            $table->string('recipient_name');
            $table->string('subject');
            $table->longText('content'); // Final content with placeholders filled
            
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            
            // Approval
            $table->foreignId('approver_id')->nullable()->constrained('employees');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
        Schema::dropIfExists('letter_templates');
    }
};
