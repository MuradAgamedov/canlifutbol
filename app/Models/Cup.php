<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cup extends Model
{
    protected $guarded = [];

    protected $casts = [
        'last_collect_time' => 'datetime',
    ];
}
