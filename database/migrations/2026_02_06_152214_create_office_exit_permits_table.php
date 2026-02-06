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
        Schema::create('office_exit_permits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            
            // "Jenis Meninggalkan Kantor": Dinas Luar | Meninggalkan Pekerjaan
            $table->enum('exit_type', ['dinas_luar', 'personal']); 
            
            // "Keperluan": Dinas Luar | Pribadi (Seems redundant with above but requested)
            // Let's assume 'purpose' field might hold detail or just enum.
            // Using logic: if exit_type is dinas, usually purpose is dinas.
            $table->enum('purpose', ['dinas_luar', 'pribadi']); 
            
            $table->time('out_time'); // Keluar Pukul
            $table->boolean('is_returning')->default(true); // Kembali / Tidak Kembali
            $table->time('return_time')->nullable(); // Jika Kembali, Pada Pukul
            
            $table->text('reason'); // Keterangan
            
            // Approval Statuses
            $table->boolean('approved_by_supervisor')->default(false); 
            $table->boolean('acknowledged_by_hcs')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_exit_permits');
    }
};
