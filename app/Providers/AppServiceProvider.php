<?php

namespace App\Providers;

use App\Http\View\Composers\NavbarComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\SidebarComposer;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.navbar', NavbarComposer::class);
        // Share sidebar data with specific views that need it
        View::composer(
            ['home', 'phim', 'xem', 'search', 'components.sidebar-popular', 'lastest', 'calendar', 'top-view', 'complete', 'tag-detail'],
            SidebarComposer::class
        );
    }
}

