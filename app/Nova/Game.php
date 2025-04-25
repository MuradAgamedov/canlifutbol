<?php

namespace App\Nova;

use App\Models\League as ModelsLeague;
use App\Models\Season as ModelsSeason;
use App\Models\Team as ModelsTeam;
use App\Nova\League;
use App\Nova\Season;
use App\Nova\Team;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

class Game extends Resource
{
    public static $model = \App\Models\Game::class;

    public static $title = 'id';

    public static $search = [
        'id',
        'game_id',
        'home_club_name',
        'away_club_name',
        'league_name'
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Number::make('Game ID', 'game_id')->sortable(),

            BelongsTo::make('Home Team', 'homeTeam', Team::class),
            BelongsTo::make('Away Team', 'awayTeam', Team::class),

            Text::make('Home Club Name')->sortable(),
            Text::make('Away Club Name')->sortable(),

            Number::make('Home Club Goals')->sortable(),
            Number::make('Away Club Goals')->sortable(),

            Number::make('Bet 1', 'bet1')->sortable(),
            Number::make('Bet X', 'betx')->sortable(),
            Number::make('Bet 2', 'bet2')->sortable(),

            BelongsTo::make('League', 'league', League::class)->nullable(),
            BelongsTo::make('Season', 'season', Season::class)->nullable(),

            DateTime::make('Start Time', 'start_time')->nullable(),
            DateTime::make('Last Update Time', 'last_update_time')->nullable(),

            Number::make('Status')->sortable(),
        ];
    }

    public function cards(NovaRequest $request)
    {
        return [];
    }

    public function filters(NovaRequest $request)
    {
        return [];
    }

    public function lenses(NovaRequest $request)
    {
        return [];
    }

    public function actions(NovaRequest $request)
    {
        return [];
    }
}
