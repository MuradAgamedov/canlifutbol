<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class Lang extends Resource
{
    use HasSortableRows;
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Lang>
     */
    public static $model = \App\Models\Lang::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'code',
        'site_code',
        'name'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')->rules(['required']),
            Text::make('Code')->rules(['required']),
            Text::make('Site_code')->rules(['required']),
            Boolean::make('Is_main_lang')
                ->trueValue(true)
                ->falseValue(false)
                ->default(false)
                ->sortable(),
            Boolean::make('Status')
                ->trueValue(true)
                ->falseValue(false)
                ->default(true)
                ->sortable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    /**
     * Perform any actions required before saving the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return void
     */
    public static function beforeSave(NovaRequest $request, $model)
    {
        if ($model->is_main_lang) {
            // Set all other languages to not be the main language
            \App\Models\Lang::where('id', '!=', $model->id)
                ->update(['is_main_lang' => false]);
        }
        if ($model->admin_panel_main_lang) {
            // Set all other languages to not be the main language
            \App\Models\Lang::where('id', '!=', $model->id)
                ->update(['admin_panel_main_lang' => false]);
        }
    }
}
