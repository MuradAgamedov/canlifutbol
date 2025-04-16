<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    public function game()
    {
        return $this->belongsTo(\App\Models\Game::class, 'game_id');
    }

    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class, 'team_id');
    }

    public function player()
    {
        return $this->belongsTo(\App\Models\Player::class, 'player_id');
    }

    public function assistPlayer()
    {
        return $this->belongsTo(\App\Models\Player::class, 'assist_palyer_id');
    }
}
