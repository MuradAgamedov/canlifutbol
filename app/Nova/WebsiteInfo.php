<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Panel;

class WebsiteInfo extends Resource
{
    public static $model = \App\Models\WebsiteInfo::class;

    public static $title = 'id';

    public static $search = ['id'];

    public function fields(Request $request)
    {
        return [
            // Translatable fields
            NovaTabTranslatable::make([
                Text::make('Copyright Text', 'copyright_text'),
                Text::make('Logo header alt text', 'logo_header_alt_text'),
                Text::make('Footer footer alt text', 'logo_footer_alt_text'),
            ]),

            // Logo Header
            Image::make('Logo Header', 'logo_header')
                ->storeAs(function (Request $request) {
                    $file = $request->file('logo_header');
                    $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    return $filename . '_' . time() . '.' . $extension;
                }),

            // Logo Footer
            Image::make('Logo Footer', 'logo_footer')
                ->storeAs(function (Request $request) {
                    $file = $request->file('logo_footer');
                    $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    return $filename . '_' . time() . '.' . $extension;
                }),

            // Social links and contact numbers
            new Panel('Social Links', [
                Text::make('Facebook', 'facebook_link'),
                Text::make('Instagram', 'instagram_link'),
                Text::make('Twitter', 'twitter_link'),
                Text::make('WhatsApp', 'whatsapp_number'),
                Text::make('Phone 1', 'number_1'),
                Text::make('Phone 2', 'number_2'),
            ]),
        ];
    }
}
