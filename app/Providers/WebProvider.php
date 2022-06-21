<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class WebProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            ['web.block._sidebar'],
            'App\Http\ViewComposers\Web\SidebarComposer'
        );
        view()->composer(
            ['web.header', 'web.footer'],
            'App\Http\ViewComposers\Web\MenuComposer'
        );
    }
}
