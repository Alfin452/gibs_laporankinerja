<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_app_settings_table.php

    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            // Koordinat Pusat Sekolah
            $table->decimal('school_latitude', 10, 8);
            $table->decimal('school_longitude', 11, 8);
            // Radius dalam meter (misal: 100)
            $table->integer('radius_meters')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
