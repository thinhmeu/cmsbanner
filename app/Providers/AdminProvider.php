<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        LengthAwarePaginator::$defaultView = "pagination::bootstrap-4";
//        LengthAwarePaginator::$defaultView = "admin.block.pagination";
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            ['admin.sidebar'],
            'App\Http\ViewComposers\Admin\SidebarComposer'
        );
    }
}
