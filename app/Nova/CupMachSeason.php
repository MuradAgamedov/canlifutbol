<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;

class CupMachSeason extends Resource
{
    public static $model = \App\Models\CupMachSeason::class;

    public static $title = 'title';

    public static $search = ['id', 'title', 'league_sub_id'];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Number::make('League ID')->sortable()->nullable(),
            Text::make('Title')->sortable()->nullable(),
            Text::make('Standing Gets At')->nullable(),
            Text::make('League Sub ID')->nullable(),
            DateTime::make('Created At')->exceptOnForms(),
            DateTime::make('Updated At')->exceptOnForms(),
        ];
    }
}
