<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Image;

class Team extends Resource
{
    public static $model = \App\Models\Team::class;

    public static $title = 'team_name';

    public static $search = ['id', 'team_name', 'slug'];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Number::make('Team ID', 'team_id')->sortable(),
            Text::make('Team Name')->sortable(),
            Text::make('Slug')->sortable(),
            Image::make('Logo')
                ->disk('public')
                ->path('teams')
                ->nullable(),

            BelongsTo::make('League', 'league', League::class)->nullable(),
            Text::make('City')->nullable(),
            Text::make('Stadium')->nullable(),
            Text::make('Date')->nullable(),
            Text::make('Address')->nullable(),
            Text::make('Website')->nullable(),
            Text::make('Stadium Capacity')->nullable(),
        ];
    }
}
