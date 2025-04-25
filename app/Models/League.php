<?php

namespace App\Models;

use App\Helpers\LeagueHelper;
use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Str;
use Spatie\EloquentSortable\SortableTrait;
class League extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SortableTrait, ImageTrait;
    public $sortable = [
        'order_column_name' => 'pos',
        'sort_when_creating' => true,
    ];
    protected $connection = 'mysql';
    protected $hidden = [
        'slug', 'updated_at', 'created_at', 'country_id', 'last_collect_time', 'type', 'league_id'
    ];
    protected $guarded = [];
    public $timestamps = false;

    public function cntry()
    {
        return $this->belongsTo(Country::class, 'countryId', 'id');
    }

    public function getLeagueImageAttribute()
    {
        return LeagueHelper::getLeagueImage($this->league_id, $this->name);
    }

    public function favorite_games()
    {
        $favoritesIds = Cookie::get('favorites');
        $favoritesIds = explode(',', str_replace(['[', ']', '"'], '', $favoritesIds));


        return $this->hasMany(Game::class, 'league_id', 'league_id')->whereIn('game_id', $favoritesIds)
            ->orderBy('league_id')->whereNotNull('last_update_time');
    }

    public function table()
    {
        return $this->hasMany(LeaguesTable::class, 'league_id');
    }

    public function tomorrow_games()
    {
        $timezone = new \DateTimeZone('Asia/Baku');
        $tomorrow_start = \Carbon\Carbon::tomorrow($timezone)->startOfDay()->timestamp;
        $tomorrow_end = \Carbon\Carbon::tomorrow($timezone)->endOfDay()->timestamp;

        return $this->hasMany(Game::class, 'league_id', 'league_id')
            ->whereBetween(\DB::raw('start_time + 14400'), [$tomorrow_start, $tomorrow_end])
            ->orderByDesc('start_time')
            ->whereNotNull('last_update_time');
    }

    public function yesterday_games()
    {
        $timezone = new \DateTimeZone('Asia/Baku');
        $yesterday_start = \Carbon\Carbon::yesterday($timezone)->startOfDay()->timestamp;
        $yesterday_end = \Carbon\Carbon::yesterday($timezone)->endOfDay()->timestamp;

        return $this->hasMany(Game::class, 'league_id', 'league_id')
            ->whereBetween(\DB::raw('start_time + 14400'), [$yesterday_start, $yesterday_end])
            ->orderByDesc('start_time')
            ->whereNotNull('last_update_time');
    }

    public function active_games()
    {
        $timezone = new \DateTimeZone('Asia/Baku');
        $today_start = \Carbon\Carbon::today($timezone)->startOfDay()->timestamp;
        $today_end = \Carbon\Carbon::today($timezone)->endOfDay()->timestamp;

        return $this->hasMany(Game::class, 'league_id', 'league_id')
            ->whereBetween(\DB::raw('start_time + 14400'), [$today_start, $today_end])
            ->orderByDesc('start_time')
            ->whereNotNull('last_update_time');
    }

    public function prediction_games()
    {
        $timezone = new \DateTimeZone('Asia/Baku');
        $today_start = \Carbon\Carbon::today($timezone)->startOfDay()->timestamp;
        $today_end = \Carbon\Carbon::today($timezone)->endOfDay()->timestamp;

        return $this->hasMany(Game::class, 'league_id', 'league_id')
            ->where(function($query) {
                $query->whereNotNull('betx')
                    ->where('betx', '!=', '');
            })
            ->whereBetween(\DB::raw('start_time + 14400'), [$today_start, $today_end])
            ->whereNotNull('last_update_time')
            ->orderByDesc('start_time');
    }

    public function finished_games()
    {
        $timezone = new \DateTimeZone('Asia/Baku');
        $currentTime = \Carbon\Carbon::now($timezone)->timestamp;

        return $this->hasMany(Game::class, 'league_id', 'league_id')
            ->whereIn('status', [4, -1]) // Add status 4 or -1 condition
            ->orderByDesc('start_time')
            ->whereNotNull('last_update_time')->take(10);
    }


    public function not_started_games()
    {
        $timezone = new \DateTimeZone('Asia/Baku');
        $currentTime = \Carbon\Carbon::now($timezone)->timestamp;

        return $this->hasMany(Game::class, 'league_id', 'league_id')
            ->where('status', 0)
            ->orderBy('start_time')
            ->whereNotNull('last_update_time');
    }

    public function live_games()
    {
        $timezone = new \DateTimeZone('Asia/Baku');
        $currentTime = \Carbon\Carbon::now($timezone)->timestamp;

        return $this->hasMany(Game::class, 'league_id', 'league_id')
            ->whereIn('status', [1, 2, 3])
            ->orderByDesc('start_time')
            ->whereNotNull('last_update_time');
    }

    public function games()
    {
        return $this->hasMany(Game::class, 'league_id', 'league_id')
            ->orderByDesc('start_time')
            ->whereNotNull('last_update_time');
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'country_id', 'country_id');
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'league_id', 'league_id')
            ->whereNull('last_collect_time');
    }

    /**
     * Boot method to automatically generate slug when a league is created or updated.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($league) {
            if (empty($league->slug)) {
                $league->slug = Str::slug($league->name);
            }
        });

        static::updating(function ($league) {
            if ($league->isDirty('name')) {
                $league->slug = Str::slug($league->name);
            }
        });
    }

    public function seasons()
    {
        return $this->hasMany(Season::class,'league_id', 'league_id');
    }
    public function lastSeason()
    {
        return $this->hasOne(Season::class, 'league_id', 'league_id')
            ->oldest('id');
    }


    public function standings()
    {
        return $this->hasMany(Standing::class,'league_id', 'league_id');
    }

    public function stats()
    {
        return $this->hasMany(PlayerTech::class,'league_id', 'league_id');
    }


    public function tGames()
    {
        return $this->hasMany(Tgame::class, 'league_id', 'league_id')
            ->orderByDesc('start_time');
    }
}
