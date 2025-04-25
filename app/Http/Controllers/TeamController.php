<?php

namespace App\Http\Controllers;

use App\Models\CupGame;
use App\Models\League;
use App\Models\Team;
use App\Models\Standing;
use App\Models\TGame;
use App\Traits\H2HgamesTrait;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    use H2HgamesTrait;
    public function index($slug)
    {
        $league = League::with(["lastSeason"])->where('slug', $slug)->firstOrFail();
        $country = $league->country;
        $area = $country->area;



        $teams = Standing::where("season_id",$league->lastSeason->id )->get();

        return view('pages.teams.index', compact('country', 'league', 'area', 'teams'));
    }

    public function details($leagueSlug, $clubSlug)
    {
        $league = League::with(["lastSeason"])->where('slug', $leagueSlug)->firstOrFail();
        $team = Team::with(['players', 'league'])->where("slug", $clubSlug)->first();

        $coach = $team->players->firstWhere('position', 'Coach');

        $players = $team->players->filter(function ($player) {
            return $player->position !== 'Coach';
        });
        $matches = $this->getTeamMatches($team, 30);

        return view('pages.teams.details', compact("team", "players", "coach", 'league', 'matches'));
    }


    public function getTeamMatches($team, $paginate)
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

        $tgameMatches = TGame::select($this->matchColumns)
            ->where(function ($query) use ($team) {
                $query->where('home_club_id', $team->team_id)
                    ->orWhere('away_club_id', $team->team_id);
            })
            ->whereNotNull('home_club_goals')
            ->whereNotNull('away_club_goals')
            ->where('home_club_goals', '!=', '')
            ->where('away_club_goals', '!=', '');

        // Union nəticəsini əldə edirik və kolleksiyaya çeviririk
        $matches = $tgameMatches->union($cupMatches)
            ->orderByRaw("STR_TO_DATE(start_time, '%Y-%m-%d %H:%i:%s') DESC")
            ->get();

        // Kolleksiyanı manuell şəkildə paginate edirik
        $currentPage = request()->input('page', 1);
        $items = $matches->slice(($currentPage - 1) * $paginate, $paginate)->all();
        $paginatedMatches = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $matches->count(),
            $paginate,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return $paginatedMatches;
    }
}
