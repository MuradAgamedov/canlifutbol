<?php
namespace App\Traits;
use App\Helpers\Shared;
use App\Models\Game;
use App\Models\League;
use Illuminate\Support\Facades\Cookie;

trait LeaguesAndGamesTrait {
    public function getGames($type)
    {
        $timezone = new \DateTimeZone(SETTINGS['timezone']);
        $todayStart = \Carbon\Carbon::today($timezone)->startOfDay()->timestamp;
        $todayEnd = \Carbon\Carbon::today($timezone)->endOfDay()->timestamp;

        $query = Game::whereNotNull('last_update_time')
            ->select(
                'game_id',
                'league_name',
                'league_id',
                'home_club_id',
                'away_club_id',
                'home_club_name',
                'away_club_name',
                'start_time',
                'last_update_time',
                'home_club_goals',
                'away_club_goals',
                'home_club_half_score',
                'away_club_half_score',
                'home_club_yellow_cards_count',
                'away_club_yellow_cards_count',
                'home_club_red_cards_count',
                'away_club_red_cards_count'
            );

        if ($type == "active" || $type == "today") {
            $query->whereBetween(\DB::raw('start_time + 14400'), [$todayStart, $todayEnd])
                ->orderByDesc(\DB::raw('start_time + 14400'));
        } elseif ($type == 'live') {
            $query->whereIn('status', [1, 2, 3])
                ->orderByDesc('start_time');
        } elseif ($type == 'yesterday') {
            $query->whereBetween(\DB::raw('start_time + 14400'), [
                strtotime('yesterday 00:00'),
                strtotime('yesterday 23:59:59')
            ])->orderByDesc(\DB::raw('start_time + 14400'));
        } elseif ($type == 'finished') {
            $query->whereIn('status', [4, -1])
                ->orderByDesc('start_time');
        } elseif ($type == 'notStarted') {
            $query->where('status', 0)
                ->orderByDesc('start_time');
        } elseif ($type == 'tomorrow') {
            $query->whereBetween(\DB::raw('start_time + 14400'), [
                strtotime('tomorrow 00:00'),
                strtotime('tomorrow 23:59:59')
            ])->where('status', 0)
                ->orderByDesc('start_time');
        } elseif ($type == 'prediction') {
            $query->whereNotNull('betx')
                ->where('betx', '!=', '')
                ->whereBetween(\DB::raw('start_time + 14400'), [$todayStart, $todayEnd])
                ->orderByDesc(\DB::raw('start_time + 14400'));
        } elseif ($type == 'favorite') {
            $favoritesIds = Cookie::get('favorites');
            $favoritesIds = explode(',', str_replace(['[', ']', '"'], '', $favoritesIds));
            $query->whereIn('id', $favoritesIds)
                ->orderBy('league_id');
        }

        return $query->paginate(50);
    }


    public function getLeaguesAndGames($type, $limit = 20)
    {
        $leagues = null;
        $games = null;

        if (request()->order == "league" || request()->order == "") {
            $leagues = League::with(["{$type}_games", 'country'])
                ->whereHas("{$type}_games", function ($query) {
                    $query->whereNotNull('id'); // Əlaqəli oyunların mövcudluğunu yoxlayır
                })
                ->orderBy('pos')
                ->paginate($limit);
        }
        if (request()->order == "time") {

            $games = $this->getGames($type);
        }

        if (Shared::getGameOrder() == 'time') {
            $games = $this->getGames($type);
        } else {
            $leagues = League::with(["{$type}_games", 'country'])
                ->whereHas("{$type}_games", function ($query) {
                    $query->whereNotNull('id'); // Əlaqəli oyunların mövcudluğunu yoxlayır
                })
                ->orderBy('pos')
                ->paginate($limit);
        }

        return compact('leagues', 'games');
    }


    public function getSelectedTypeLeagues($type)
    {
        return League::with(['lastSeason'])->whereHas("{$type}_games")->orderBy('pos')->get();
    }
}
