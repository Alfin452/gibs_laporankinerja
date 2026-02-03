<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_activity_types_table.php

    public function up(): void
    {
        Schema::create('activity_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Contoh: 'K2', 'K3'
            $table->string('name'); // Contoh: 'Substitute Teacher', 'Morning Briefing'

            // 'boolean' = Hadir/Tidak (1/0)
            // 'numeric' = Butuh input angka (jam/jumlah)
            $table->enum('input_type', ['boolean', 'numeric'])->default('boolean');

            $table->boolean('is_active')->default(true); // Agar bisa non-aktifkan kegiatan lama
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_types');
    }
};
