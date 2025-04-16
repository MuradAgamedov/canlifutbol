<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Standing extends Model
{
    protected $guarded = [];

    public function league()
    {
        return $this->belongsTo(\App\Models\League::class, 'league_id');
    }

    public function season()
    {
        return $this->belongsTo(\App\Models\Season::class, 'season_id');
    }

    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class, 'team_id');
    }
}
