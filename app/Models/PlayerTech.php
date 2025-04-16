<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerTech extends Model
{
    protected $table = "player_techs";
    protected $guarded = [];

    public function player()
    {
        return $this->belongsTo(\App\Models\Player::class, 'player_id');
    }

    public function league()
    {
        return $this->belongsTo(\App\Models\League::class, 'league_id');
    }

    public function season()
    {
        return $this->belongsTo(\App\Models\Season::class, 'season_id');
    }
}
