<?php

namespace App\Models;

use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory, ImageTrait;

    public function league()
    {
        return $this->belongsTo(\App\Models\League::class, 'league_id');
    }
    public function players()
    {
        return $this->hasMany(\App\Models\Player::class, 'teamId', "team_id");
    }
}
