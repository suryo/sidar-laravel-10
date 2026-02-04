<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g., "Dashboard", "Presensi & Cuti"
            $table->string('route_name')->nullable(); // e.g., "dashboard"
            $table->string('url')->nullable(); 
            $table->text('icon_svg')->nullable(); // Store the SVG path 'd' attribute or full SVG
            $table->boolean('is_header')->default(false); // If true, it's a section header
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('menu_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_role');
        Schema::dropIfExists('menus');
    }
};
