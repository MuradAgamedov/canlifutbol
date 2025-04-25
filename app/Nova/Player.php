<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

use App\Nova\CountryTeam;

class Player extends Resource
{
    public static $model = \App\Models\Player::class;

    public static $title = 'name';

    public static $search = ['id', 'name', 'playerId', 'slug'];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')->sortable()->nullable(),
            Text::make('Player ID', 'playerId')->nullable(),
            Text::make('Record ID', 'recordId')->nullable(),
            Text::make('Slug')->nullable(),

            BelongsTo::make('Team', 'team', Team::class)->nullable(),
            BelongsTo::make('Country Team', 'countryTeam', CountryTeam::class)->nullable(),

            Text::make('Country')->nullable(),
            Text::make('Position')->nullable(),
            Text::make('Feet')->nullable(),
            Text::make('Birthday')->nullable(),
            Number::make('Height')->nullable(),
            Number::make('Weight')->nullable(),
            Number::make('Number')->nullable(),
            Number::make('Country Team Number')->nullable(),

            Text::make('Value')->nullable(),
            Text::make('Contract End Date', 'contractEndDate')->nullable(),
            Text::make('Introduce')->nullable(),

            Number::make('Team Type')->nullable(),
            Number::make('Update Time')->nullable(),
            Boolean::make('Updated')->nullable(),

            Image::make('Photo')
                ->disk('public')
                ->path('players')
                ->nullable(),


            DateTime::make('Last Image Collect Time')->nullable(),
        ];
    }
}
