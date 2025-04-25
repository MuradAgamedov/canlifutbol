<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Resource;

class Area extends Resource
{
    public static $model = \App\Models\Area::class;

    public static $title = 'title';

    public static $search = [
        'id', 'title', 'slug',
    ];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Number::make('Area ID', 'area_id')
                ->sortable()
                ->rules('required', 'integer'),

            Text::make('Slug')
                ->sortable()
                ->rules('required', 'max:255'),

            Image::make('Icon')
                ->disk('public') // storage/app/public
                ->path('areas/icons') // yüklənəcəyi qovluq
                ->nullable()
                ->prunable(), // silindikdə faylı da silsin
        ];
    }
}
