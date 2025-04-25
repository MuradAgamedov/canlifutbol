<?php

namespace App\Models;

use App\Helpers\Functions;
use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class Country extends Model
{
    use HasFactory, ImageTrait;
    protected $guarded = [];
    protected $connection = 'mysql';
    protected $hidden = [
        'country_id', 'flag', 'slug', 'status', 'area_id', 'created_at', 'updated_at'
    ];

    public function continent()
    {
        return $this->hasMany(Continent::class, 'code', 'continentCode');
    }

    public function leagues()
    {
        return $this->hasMany(League::class, 'country_id', 'country_id')
            ->orderBy('pos');
    }

    public function getCountryCode3Attribute()
    {
        return Functions::get_2_code_from_country_name($this->name);
    }

    public function a_cities()
    {
        return $this->hasMany(AmateurCity::class, 'country_id', 'country_id');
    }

    /**
     * Boot method to handle events and run optimize clear.
     */
    protected static function boot()
    {
        parent::boot();

        // Handle "saving" event
        static::saving(function () {
            self::clearOptimize();
        });

        // Handle "deleting" event
        static::deleting(function () {
            self::clearOptimize();
        });
    }

    /**
     * Clear cache and run optimize clear command.
     *
     * @return void
     */
    protected static function clearOptimize()
    {
        Artisan::call('optimize:clear');
    }

    // app/Models/Country.php
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', "area_id");
    }

}
