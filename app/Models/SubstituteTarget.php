<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubstituteTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'terlaksana_count', // Tambahkan ini
        'alpha_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
