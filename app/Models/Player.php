<?php

namespace App\Models;

use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use ImageTrait;
    public $timestamps = false;

    protected $guarded = [];
    protected $casts = [
        'last_image_collect_time' => 'datetime',
    ];
    public function countryTeam()
    {
        return $this->belongsTo(\App\Models\CountryTeam::class, 'country_team_id');
    }
    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class, 'teamId', 'team_id'); // teamId varchar, Team modelində team_id
    }
}
