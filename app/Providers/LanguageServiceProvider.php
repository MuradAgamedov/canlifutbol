<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Default locale
        $defaultLocale = ['az'];

        if (Schema::hasTable('langs')) {
            $locales = DB::table('langs')->pluck('code')->toArray();

            if (empty($locales)) {
                $locales = $defaultLocale;
            }
        } else {
            $locales = $defaultLocale;
        }

        Config::set('tab-translatable.locales', $locales);
    }
}
