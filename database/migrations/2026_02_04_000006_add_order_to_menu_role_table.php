<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_role', function (Blueprint $table) {
            $table->integer('order')->default(0);
        });

        // Initialize pivot order with the global menu order
        $menus = DB::table('menus')->get();
        foreach ($menus as $menu) {
            DB::table('menu_role')
                ->where('menu_id', $menu->id)
                ->update(['order' => $menu->order]);
        }
    }

    public function down(): void
    {
        Schema::table('menu_role', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
