<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

use App\Nova\Team;
use App\Nova\CupMachSeason;

class CupGame extends Resource
{
    public static $model = \App\Models\CupGame::class;

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
            BelongsTo::make('Season', 'season', CupMachSeason::class)->nullable(),
            BelongsTo::make('League', 'league', Cup::class)->nullable(),
            Text::make('League Name')->nullable(),
            Text::make('Home Club Name')->nullable(),
            Text::make('Away Club Name')->nullable(),

            Text::make('Start Time')->nullable(),
            Text::make('Last Update Time')->nullable(),

            Text::make('Home Club Goals')->nullable(),
            Text::make('Away Club Goals')->nullable(),
            Text::make('Home Club Half Score')->nullable(),
            Text::make('Away Club Half Score')->nullable(),

            Number::make('Home Yellow Cards', 'home_club_yellow_cards_count')->nullable(),
            Number::make('Away Yellow Cards', 'away_club_yellow_cards_count')->nullable(),
            Number::make('Home Red Cards', 'home_club_red_cards_count')->nullable(),
            Number::make('Away Red Cards', 'away_club_red_cards_count')->nullable(),

            Number::make('Home Corners', 'home_club_corners_count')->nullable(),
            Number::make('Away Corners', 'away_club_corners_count')->nullable(),

            Text::make('Games')->nullable(),

            Text::make('Bet 1', 'bet1'),
            Text::make('Bet X', 'betx'),
            Text::make('Bet 2', 'bet2'),

            Text::make('Status')->nullable(),
            Text::make('Added/Old', 'addedd_add_old'),
            Text::make('Group')->nullable(),
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
