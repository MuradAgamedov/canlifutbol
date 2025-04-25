<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;

class Country extends Resource
{
    public static $model = \App\Models\Country::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'slug'
    ];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Number::make('Country ID', 'country_id')
                ->sortable()
                ->rules('required'),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Image::make('Flag')
                ->disk('public')
                ->path('country_flags') // Fayllar storage/app/public/country/flag/ içində olacaq
                ->nullable(),


            Text::make('Slug')
                ->sortable()
                ->rules('required', 'max:255'),

            Select::make('Status')->options([
                1 => 'Active',
                0 => 'Inactive',
            ])->displayUsingLabels()->nullable(),

            Number::make('Position', 'pos')->nullable(),

            // Dropdown olaraq Area seçimi
            BelongsTo::make('Area', 'area', Area::class)->nullable(),
        ];
    }
}
