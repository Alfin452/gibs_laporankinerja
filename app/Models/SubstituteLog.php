<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubstituteLog extends Model
{
    protected $fillable = ['user_id', 'date', 'terlaksana', 'alpha'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
