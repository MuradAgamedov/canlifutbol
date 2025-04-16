<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

use App\Nova\League;
use App\Nova\CupMachSeason;
use App\Nova\Team;

class CupGroupStanding extends Resource
{
    public static $model = \App\Models\CupGroupStanding::class;

    public static $title = 'team_id';

    public static $search = ['id', 'team_id', 'rank', 'group'];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('League', 'league', League::class)->nullable(),
            BelongsTo::make('Season', 'season', CupMachSeason::class)->nullable(),
            BelongsTo::make('Team', 'team', Team::class)->nullable(),

            Text::make('Rank')->nullable(),
            Text::make('Group')->nullable(),
            Text::make('Total')->nullable(),
            Text::make('Win')->nullable(),
            Text::make('Draw')->nullable(),
            Text::make('Loses')->nullable(),
            Text::make('Scored')->nullable(),
            Text::make('Conceded')->nullable(),
            Text::make('Diff')->nullable(),
            Text::make('Points')->nullable(),
        ];
    }
}
