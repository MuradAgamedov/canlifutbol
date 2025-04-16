<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

use App\Nova\Team;
use App\Nova\League;
use App\Nova\CupMachSeason; // Əgər Season-dursa dəyiş

class TGame extends Resource
{
    public static $model = \App\Models\TGame::class;

    public static $title = 'game_id';

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

            BelongsTo::make('Home Team', 'homeTeam', Team::class)->nullable(),
            BelongsTo::make('Away Team', 'awayTeam', Team::class)->nullable(),

            Text::make('Home Club Name')->sortable()->nullable(),
            Text::make('Away Club Name')->sortable()->nullable(),

            BelongsTo::make('League', 'league', League::class)->nullable(),
            BelongsTo::make('Season', 'season', CupMachSeason::class)->nullable(),

            Text::make('League Name')->nullable(),
            Text::make('Start Time')->nullable(),
            Text::make('Last Update Time')->nullable(),

            Text::make('Home Club Goals')->nullable(),
            Text::make('Away Club Goals')->nullable(),
            Text::make('Home Club Half Score')->nullable(),
            Text::make('Away Club Half Score')->nullable(),

            Text::make('Home Club Yellow Cards')->nullable(),
            Text::make('Away Club Yellow Cards')->nullable(),
            Text::make('Home Club Red Cards')->nullable(),
            Text::make('Away Club Red Cards')->nullable(),

            Text::make('Games')->nullable(),
            Text::make('Bet 1', 'bet1'),
            Text::make('Bet X', 'betx'),
            Text::make('Bet 2', 'bet2'),
            Text::make('Status')->nullable(),

            Text::make('Home Corners')->nullable(),
            Text::make('Away Corners')->nullable(),

            Text::make('Round')->nullable(),
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
