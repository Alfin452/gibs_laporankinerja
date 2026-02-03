<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $guarded = ['id'];

    public static function getSettings()
    {
        return self::firstOrNew([], [
            'school_latitude' => -3.229683,
            'school_longitude' => 114.598840,
            'radius_meters' => 350
        ]);
    }
}
