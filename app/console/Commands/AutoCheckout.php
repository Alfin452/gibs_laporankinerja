<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use Carbon\Carbon;

class AutoCheckout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:auto-checkout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Melakukan checkout otomatis bagi guru yang lupa absen pulang';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        // Cari yang sudah absen masuk (clock_in ada) TAPI belum absen pulang (clock_out NULL)
        $lupaAbsen = Attendance::whereDate('date', $today)
            ->whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->get();

        $count = 0;
        foreach ($lupaAbsen as $absen) {
            // Set jam pulang otomatis ke 16:00
            $absen->update([
                'clock_out' => $today->format('Y-m-d') . ' 16:00:00',
                // Opsional: Tandai di notes atau lat/long dummy jika perlu
                'lat_out' => $absen->lat_in, // Pakai lokasi masuk saja
                'long_out' => $absen->long_in,
            ]);
            $count++;
        }

        $this->info("Berhasil melakukan auto-checkout untuk $count orang.");
    }
}