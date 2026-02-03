<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $guarded = ['id'];

    // Helper untuk mengambil settingan dengan mudah
    public static function getSettings()
    {
        return self::firstOrNew([], [
            'school_latitude' => -3.3194, // Contoh Lat GIBS (bisa diedit nanti)
            'school_longitude' => 114.5908,
            'radius_meters' => 100
        ]);
    }
}
