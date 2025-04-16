<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CupMachSeason extends Model
{
    protected $table = "cupmachseasons";
    public $timestamps = true;

    protected $fillable = [
        'league_id',
        'title',
        'standing_gets_at',
        'league_sub_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
