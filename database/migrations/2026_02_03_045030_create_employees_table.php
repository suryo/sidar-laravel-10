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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 50)->unique();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20)->nullable();
            
            // Organization
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('division_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->string('unit_usaha', 100)->nullable();
            $table->string('position', 100)->nullable();
            $table->enum('level', ['staff', 'supervisor', 'manager', 'director', 'owner'])->default('staff');
            
            // Approval Chain (IDs only, no FK constraints to avoid circular dependency)
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->unsignedBigInteger('senior_manager_id')->nullable();
            $table->unsignedBigInteger('director_id')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            
            // Leave & Attendance
            $table->integer('leave_quota')->default(12);
            $table->string('leave_group', 50)->nullable();
            $table->decimal('max_hours', 5, 2)->default(8.00);
            $table->boolean('can_attend_outside')->default(false);
            
            // Status
            $table->enum('status', ['active', 'inactive', 'resigned'])->default('active');
            $table->date('join_date')->nullable();
            $table->date('resign_date')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('nik');
            $table->index('department_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
