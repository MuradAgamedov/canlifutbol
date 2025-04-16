<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryTeam extends Model
{
    protected $guarded = [];

    public function league()
    {
        return $this->belongsTo(\App\Models\League::class, 'league_id');
    }
}
