<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    public function league()
    {
        return $this->belongsTo(\App\Models\League::class, 'league_id');
    }
}
