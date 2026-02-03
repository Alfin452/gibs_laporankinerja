<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_activity_logs_table.php

    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Relasi ke jenis kegiatan (K2, K3, dst)
            $table->foreignId('activity_type_id')->constrained('activity_types')->onDelete('cascade');

            $table->date('date')->index();
            $table->time('time_recorded'); // Jam saat tombol simpan ditekan

            // Nilai kegiatan. 
            // Jika tipe 'boolean', isi 1. 
            // Jika tipe 'numeric' (K2), isi jumlah jam (misal 2.5).
            $table->decimal('value', 8, 2)->default(1);

            // Bukti Lokasi
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
