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
            if (!Schema::hasColumn('dars', 'hcs_status')) {
                $table->enum('hcs_status', ['pending', 'approved', 'rejected'])->nullable()->after('status');
            }
            if (!Schema::hasColumn('dars', 'hcs_approved_at')) {
                $table->timestamp('hcs_approved_at')->nullable();
            }
            if (!Schema::hasColumn('dars', 'hcs_id')) {
                $table->foreignId('hcs_id')->nullable()->constrained('employees')->nullOnDelete();
            }
            if (!Schema::hasColumn('dars', 'approved_by_id')) {
                $table->foreignId('approved_by_id')->nullable()->constrained('employees')->nullOnDelete()->comment('Supervisor who approved');
            }
        });

        Schema::table('leaves', function (Blueprint $table) {
            if (!Schema::hasColumn('leaves', 'hcs_status')) {
                $table->enum('hcs_status', ['pending', 'approved', 'rejected'])->nullable()->after('status');
            }
            if (!Schema::hasColumn('leaves', 'hcs_approved_at')) {
                $table->timestamp('hcs_approved_at')->nullable();
            }
            if (!Schema::hasColumn('leaves', 'hcs_id')) {
                $table->foreignId('hcs_id')->nullable()->constrained('employees')->nullOnDelete();
            }
            if (!Schema::hasColumn('leaves', 'approved_by_id')) {
                $table->foreignId('approved_by_id')->nullable()->constrained('employees')->nullOnDelete()->comment('Supervisor who approved');
            }
            
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
