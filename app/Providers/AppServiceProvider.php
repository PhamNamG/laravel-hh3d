<?php

namespace App\Providers;

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
        // Share sidebar data with specific views that need it
        View::composer(
            ['home', 'phim', 'xem', 'search', 'components.sidebar-popular', 'lastest'],
            SidebarComposer::class
        );
    }
}

