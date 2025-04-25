<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

use App\Nova\Game;
use App\Nova\Team;
use App\Nova\Player;

class Event extends Resource
{
    public static $model = \App\Models\Event::class;

    public static $title = 'id';

    public static $search = ['id', 'minute', 'player_name', 'assist_player'];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Game', 'game', Game::class),
            BelongsTo::make('Team', 'team', Team::class),
            BelongsTo::make('Player', 'player', Player::class)->nullable(),
            BelongsTo::make('Assist Player', 'assistPlayer', Player::class)->nullable(),

            Number::make('Event Type')->sortable(),
            Text::make('Minute')->sortable(),
            Text::make('Player Name')->nullable(),
            Text::make('Assist Player Name', 'assist_player')->nullable(),
        ];
    }
}
