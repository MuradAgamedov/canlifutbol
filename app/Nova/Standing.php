<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

use App\Nova\League;
use App\Nova\Team;
use App\Nova\Season;

class Standing extends Resource
{
    public static $model = \App\Models\Standing::class;

    public static $title = 'id';

    public static $search = ['id'];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('League', 'league', League::class)->nullable(),
            BelongsTo::make('Season', 'season', Season::class)->nullable(),
            BelongsTo::make('Team', 'team', Team::class)->nullable(),

            Number::make('Position')->sortable()->nullable(),
            Number::make('Games Played')->nullable(),
            Number::make('Wins')->nullable(),
            Number::make('Draws')->nullable(),
            Number::make('Losses')->nullable(),
            Number::make('Scored')->nullable(),
            Number::make('Conceded')->nullable(),
            Number::make('GD')->nullable(),
            Number::make('Points'),

            Text::make('Recent Results')->nullable()->hideFromIndex(),
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
