<?php

namespace App\Traits;

use App\Models\AppSetting;

trait RadiusCheck
{
    /**
     * Menghitung jarak antara dua titik koordinat (dalam meter)
     * Menggunakan rumus Haversine
     */
    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Cek apakah user berada di dalam radius sekolah
     */
    public function checkRadius($userLat, $userLong)
    {
        // Ambil settingan sekolah dari database
        $settings = AppSetting::getSettings();

        $distance = $this->calculateDistance(
            $userLat,
            $userLong,
            $settings->school_latitude,
            $settings->school_longitude
        );

        return [
            'allowed' => $distance <= $settings->radius_meters,
            'distance' => round($distance, 2), // Jarak user saat ini (meter)
            'max_radius' => $settings->radius_meters
        ];
    }
}
