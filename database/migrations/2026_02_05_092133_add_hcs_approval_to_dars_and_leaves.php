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
        Schema::table('dars', function (Blueprint $table) {
            $table->enum('hcs_status', ['pending', 'approved', 'rejected'])->nullable()->after('status');
            $table->timestamp('hcs_approved_at')->nullable();
            $table->foreignId('hcs_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('approved_by_id')->nullable()->constrained('employees')->nullOnDelete()->comment('Supervisor who approved');
        });

        Schema::table('leaves', function (Blueprint $table) {
            $table->enum('hcs_status', ['pending', 'approved', 'rejected'])->nullable()->after('status');
            $table->timestamp('hcs_approved_at')->nullable();
            $table->foreignId('hcs_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('approved_by_id')->nullable()->constrained('employees')->nullOnDelete()->comment('Supervisor who approved');
            
            // Ensure supervisor_status exists or Add it if missing in previous migration (Leaves table check needed, butassuming it exists or adding generic one)
            // Existing leaves table has supervisor_id but not explicitly distinct 'status' enum in some schemas, let's verify.
            // Based on LeaveController, it uses 'status' generally, but let's add specific tracking if needed. 
            // The requirement implies dual approval, so we need separate statuses.
            if (!Schema::hasColumn('leaves', 'supervisor_status')) {
                 $table->enum('supervisor_status', ['pending', 'approved', 'rejected'])->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('dars', function (Blueprint $table) {
            $table->dropForeign(['hcs_id']);
            $table->dropForeign(['approved_by_id']);
            $table->dropColumn(['hcs_status', 'hcs_approved_at', 'hcs_id', 'approved_by_id']);
        });

        Schema::table('leaves', function (Blueprint $table) {
             $table->dropForeign(['hcs_id']);
             $table->dropForeign(['approved_by_id']);
             $table->dropColumn(['hcs_status', 'hcs_approved_at', 'hcs_id', 'approved_by_id', 'supervisor_status']);
        });
    }
};
