<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\WebsiteInfo;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        View::composer('*', function ($view) {
            $websiteInfo = WebsiteInfo::first();
            $pages = Page::with(['children' => function ($query) {
                $query->where('show_on_header', true);
            }])
                ->whereNull('parent_id')
                ->where('show_on_header', true)
                ->get();


            $view->with('websiteInfo', $websiteInfo);
            $view->with('websiteInfo', $websiteInfo);
            $view->with('pages', $pages);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
