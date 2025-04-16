<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CupGroupStanding extends Model
{
    protected $guarded = [];

    public function league()
    {
        return $this->belongsTo(\App\Models\Cup::class, 'league_id');
    }

    public function season()
    {
        return $this->belongsTo(\App\Models\CupMachSeason::class, 'season_id');
    }

    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class, 'team_id');
    }
}
