<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $guarded = ['id'];

    // Agar kolom tanggal otomatis dikelola sebagai objek Carbon (memudahkan format tgl)
    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime:H:i', // Format jam menit
        'clock_out' => 'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
