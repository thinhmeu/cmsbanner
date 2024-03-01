<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        define('IS_AMP', 0);
        LengthAwarePaginator::$defaultView = "pagination::bootstrap-4";
        view()->composer(
            ['admin.sidebar'],
            'App\Http\ViewComposers\Admin\SidebarComposer'
        );
    }
}
