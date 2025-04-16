<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $guarded = [];

    public function countryTeam()
    {
        return $this->belongsTo(\App\Models\CountryTeam::class, 'country_team_id');
    }
    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class, 'teamId', 'team_id'); // teamId varchar, Team modelində team_id
    }
}
