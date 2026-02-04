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
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('access_area_id')->nullable()->after('location_id')->constrained()->nullOnDelete();
            $table->foreignId('business_unit_id')->nullable()->after('access_area_id')->constrained()->nullOnDelete();
            // We can drop the old unit_usaha column if we migrate data, but this is early dev so safe to drop
            if (Schema::hasColumn('employees', 'unit_usaha')) {
                $table->dropColumn('unit_usaha');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['access_area_id']);
            $table->dropForeign(['business_unit_id']);
            $table->dropColumn(['access_area_id', 'business_unit_id']);
            $table->string('unit_usaha', 100)->nullable();
        });
    }
};
