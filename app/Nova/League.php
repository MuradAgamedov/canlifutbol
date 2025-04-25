<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Image;

class League extends Resource
{
    public static $model = \App\Models\League::class;

    public static $title = 'league_name';

    public static $search = ['id', 'league_name', 'league_short_name', 'slug'];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Number::make('League ID', 'league_id')->sortable(),
            Text::make('League Name')->sortable(),
            Text::make('League Short Name')->nullable(),
            Text::make('Slug')->nullable(),
            Image::make('Logo')
                ->disk('public')
                ->path('league_match') // Fayllar storage/app/public/country/flag/ içində olacaq
                ->nullable(),

            Text::make('Text')->hideFromIndex()->nullable(),
            Number::make('Type')->nullable(),
            Number::make('Pos')->sortable(),
            Number::make('Country ID')->nullable(),
        ];
    }
}
