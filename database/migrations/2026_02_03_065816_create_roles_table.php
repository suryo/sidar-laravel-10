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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Permissions
            $table->boolean('can_write')->default(false);
            $table->boolean('can_read_own')->default(false);
            $table->boolean('can_read_division')->default(false);
            $table->boolean('can_read_department')->default(false);
            $table->boolean('can_read_all')->default(false);
            $table->boolean('can_approve')->default(false);
            $table->boolean('can_manage_users')->default(false);
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_hcs_print')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
