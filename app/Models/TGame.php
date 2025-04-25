<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TGame extends Model
{
    protected $table = "tgames";
    protected $guarded = [];

    public function homeTeam()
    {
        return $this->belongsTo(\App\Models\Team::class, 'home_club_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(\App\Models\Team::class, 'away_club_id');
    }

    public function league()
    {
        return $this->belongsTo(\App\Models\League::class, 'league_id');
    }

    public function season()
    {
        return $this->belongsTo(\App\Models\CupMachSeason::class, 'season_id');
    }
}
