<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Http\Requests\NovaRequest;

class Cup extends Resource
{
    public static $model = \App\Models\Cup::class;

    public static $title = 'league_name';

    public static $search = ['id', 'league_name', 'league_short_name', 'slug'];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Number::make('League ID')->sortable(),
            Text::make('League Name')->sortable(),
            Text::make('League Short Name')->nullable(),
            Text::make('Slug')->nullable(),

            Image::make('Logo')
                ->disk('public')
                ->preview(function ($value, $disk) {
                    return $value ? asset($value) : null;
                })
                ->readonly()
                ->exceptOnForms()
                ->nullable(),

            Text::make('Text')->hideFromIndex()->nullable(),
            Number::make('Type')->nullable(),
            Number::make('Pos')->sortable(),
            Number::make('Country ID')->nullable(),
        ];
    }
}
