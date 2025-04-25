<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Outl1ne\NovaSortable\Traits\HasSortableRows;
use Laravel\Nova\Fields\Select;
class Page extends Resource
{
    use HasSortableRows;

    public static $model = \App\Models\Page::class;

    public static $title = 'title';

    public static $search = ['title', 'slug'];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            NovaTabTranslatable::make([
                Text::make('Title', 'title')->rules('required', 'max:255'),
                Text::make('SEO Title', 'seo_title')->hideFromIndex(),
                Text::make('SEO Keywords', 'seo_keywords')->hideFromIndex(),
                Text::make('SEO Description', 'seo_description')->hideFromIndex(),
            ]),
            Image::make('Page banner', 'banner_image')
                ->storeAs(function (Request $request) {
                    $file = $request->file('banner_image');
                    $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    return $filename . '_' . time() . '.' . $extension;
                }),
            Text::make('Slug', 'slug')
                ->rules('required', 'max:255'),

            Textarea::make('SEO Header', 'seo_header')->alwaysShow(),

            Textarea::make('SEO Footer', 'seo_footer')->alwaysShow(),

            Text::make('Code', 'code'),

            Boolean::make('Show on Header', 'show_on_header'),
            Boolean::make('Show on Footer', 'show_on_footer'),


            Select::make('Parent Page', 'parent_id')
                ->options(\App\Models\Page::pluck('title', 'id'))




        ];
    }
}
