<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    protected $guarded = ['id'];

    // Relasi: Satu jenis kegiatan punya banyak log aktivitas
    public function logs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
