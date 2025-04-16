<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Team;
use App\Models\League;
class Game extends Model
{
    use HasFactory;

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_club_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_club_id');
    }

    public function league()
    {
        return $this->belongsTo(League::class, 'league_id');
    }

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id');
    }
}
