<?php

namespace App\Nova;

use App\Nova\League;
use App\Nova\Player;
use App\Nova\Season;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class PlayerTech extends Resource
{
    public static $model = \App\Models\PlayerTech::class;

    public static $title = 'player_name';

    public static $search = ['id', 'player_name', 'raiting'];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Player', 'player', Player::class)->nullable(),
            BelongsTo::make('League', 'league', League::class)->nullable(),
            BelongsTo::make('Season', 'season', Season::class)->nullable(),

            Text::make('Player Name')->nullable(),
            Number::make('Play Count')->nullable(),
            Number::make('Play Sub')->nullable(),
            Text::make('Mins')->nullable(),

            Number::make('Standard Goals', 'standart_goals')->nullable(),
            Number::make('Penalty Goals')->nullable(),
            Number::make('Shots')->nullable(),
            Number::make('Shog')->nullable(),
            Number::make('Fauls')->nullable(),
            Number::make('Best')->nullable(),
            Text::make('Raiting')->nullable(),
        ];
    }
}
