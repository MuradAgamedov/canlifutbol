<?php

namespace App\Traits;

use App\Models\CupGame;
use App\Models\Game;
use App\Models\Tgame;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait H2HgamesTrait
{
    protected array $matchColumns = [
        'game_id',
        'league_id',
        'league_name',
        'season_id',
        'home_club_id',
        'away_club_id',
        'start_time',
        'home_club_goals',
        'away_club_goals',
        'home_club_name',
        'away_club_name'
    ];

    /**
     * İki komanda arasında bütün matçları gətirir (pagination ilə)
     */
    public function getAllMatches($home_team, $away_team, $pagination=10)
    {
        $allMatches = $this->getAllMatchesWithoutPagination($home_team, $away_team);
        return $this->paginateCollection($allMatches, $pagination);
    }

    /**
     * İki komanda arasında bütün matçları gətirir (pagination olmadan)
     */

    private function getAllMatchesWithoutPagination($home_team, $away_team)
    {

        $matchColumns = [
            'game_id', 'league_id', 'league_name', 'season_id',
            'home_club_id', 'away_club_id',
            'home_club_goals', 'away_club_goals', 'home_club_name', 'away_club_name'
        ];

        $cupMatches = CupGame::select(array_merge($matchColumns, [
            \DB::raw("DATE_FORMAT(start_time, '%Y-%m-%d') as start_time")
        ]))
            ->where(function ($query) use ($home_team, $away_team) {
                $query->where('home_club_id', $home_team->team_id)
                    ->where('away_club_id', $away_team->team_id)
                    ->orWhere('home_club_id', $away_team->team_id)
                    ->where('away_club_id', $home_team->team_id);
            })
            ->whereNotNull('home_club_goals')
            ->whereNotNull('away_club_goals')
            ->where('home_club_goals', '!=', '')
            ->where('away_club_goals', '!=', '');

        $tgameMatches = Tgame::select(array_merge($matchColumns, [
            \DB::raw("DATE_FORMAT(start_time, '%Y-%m-%d') as start_time")
        ]))
            ->where(function ($query) use ($home_team, $away_team) {
                $query->where('home_club_id', $home_team->team_id)
                    ->where('away_club_id', $away_team->team_id)
                    ->orWhere('home_club_id', $away_team->team_id)
                    ->where('away_club_id', $home_team->team_id);
            })
            ->whereNotNull('home_club_goals')
            ->whereNotNull('away_club_goals')
            ->where('home_club_goals', '!=', '')
            ->where('away_club_goals', '!=', '');

        $games = Game::select(array_merge($matchColumns, [
            \DB::raw("CASE
                    WHEN start_time REGEXP '^[0-9]+$' THEN DATE_FORMAT(FROM_UNIXTIME(start_time), '%Y-%m-%d')
                    ELSE DATE_FORMAT(start_time, '%Y-%m-%d')
                  END as start_time")
        ]))
            ->where(function ($query) use ($home_team, $away_team) {
                $query->where('home_club_id', $home_team->team_id)
                    ->where('away_club_id', $away_team->team_id)
                    ->orWhere('home_club_id', $away_team->team_id)
                    ->where('away_club_id', $home_team->team_id);
            }) ->whereNotNull('home_club_goals')
            ->whereNotNull('away_club_goals');





        return $tgameMatches->union($cupMatches)->union($games)
            ->orderByRaw("STR_TO_DATE(start_time, '%Y-%m-%d') DESC")
            ->get()
            ->unique('game_id');

    }

    /**
     * Ev sahibi komandanın qalibiyyət sayını qaytarır
     */
    public function HomeWinsCount($home_team, $away_team)
    {
        $matches = $this->getAllMatchesWithoutPagination($home_team, $away_team);

        return $matches->filter(fn($match) =>
            ($match->home_club_id == $home_team->team_id && intval($match->home_club_goals) > intval($match->away_club_goals)) ||
            ($match->away_club_id == $home_team->team_id && intval($match->away_club_goals) > intval($match->home_club_goals))
        )->count();
    }

    /**
     * Səfər komandasının qalibiyyət sayını qaytarır
     */
    public function AwayWinsCount($home_team, $away_team)
    {
        $matches = $this->getAllMatchesWithoutPagination($home_team, $away_team);

        return $matches->filter(fn($match) =>
            ($match->home_club_id == $away_team->team_id && intval($match->home_club_goals) > intval($match->away_club_goals)) ||
            ($match->away_club_id == $away_team->team_id && intval($match->away_club_goals) > intval($match->home_club_goals))
        )->count();
    }

    /**
     * Heç-heçə oyunları sayır
     */
    public function DrawsCount($home_team, $away_team)
    {
        $matches = $this->getAllMatchesWithoutPagination($home_team, $away_team);

        return $matches->filter(fn($match) =>
            intval($match->home_club_goals) == intval($match->away_club_goals)
        )->count();
    }



    /**
     * Birinci matçın tarixini qaytarır
     */
    public function FirstMatchStartDate($home_team, $away_team)
    {
        $matches = $this->getAllMatchesWithoutPagination($home_team, $away_team);
        return $matches->last()->start_time ?? 'N/A';
    }

    /**
     * Bir komandanın bütün matçlarını gətirir (pagination ilə)
     */
    public function getTeamMatches($team)
    {
        $allMatches = $this->getTeamMatchesWithoutPagination($team);
        return $this->paginateCollection($allMatches, 10);
    }

    /**
     * Bir komandanın bütün matçlarını gətirir (pagination olmadan)
     */
    private function getTeamMatchesWithoutPagination($team)
    {
        $cupMatches = CupGame::select($this->matchColumns)
            ->where(function ($query) use ($team) {
                $query->where('home_club_id', $team->team_id)
                    ->orWhere('away_club_id', $team->team_id);
            })
            ->whereNotNull('home_club_goals')
            ->whereNotNull('away_club_goals')
            ->where('home_club_goals', '!=', '')
            ->where('away_club_goals', '!=', '');

        $tgameMatches = Tgame::select($this->matchColumns)
            ->where(function ($query) use ($team) {
                $query->where('home_club_id', $team->team_id)
                    ->orWhere('away_club_id', $team->team_id);
            })
            ->whereNotNull('home_club_goals')
            ->whereNotNull('away_club_goals')
            ->where('home_club_goals', '!=', '')
            ->where('away_club_goals', '!=', '');

        return $tgameMatches->union($cupMatches)
            ->orderByRaw("STR_TO_DATE(start_time, '%Y-%m-%d %H:%i:%s') DESC")
            ->get()
            ->unique('game_id');
    }

    /**
     * Matçları pagination ilə qaytarır
     */
    private function paginateCollection(Collection $items, $perPage)
    {
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $perPage;

        return new LengthAwarePaginator(
            $items->slice($offset, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}
