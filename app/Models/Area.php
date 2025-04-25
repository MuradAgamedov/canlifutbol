<?php

namespace App\Models;

use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory, ImageTrait;
    protected $guarded=[];
    protected $connection = 'mysql';
    public function countries()
    {
        return $this->hasMany(Country::class, "area_id", "area_id");
    }
}
