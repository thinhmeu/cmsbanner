<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Request;

class WebController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $segment = Request::segments();
        $checkAmp = !empty($segment) ? end($segment) == 'amp' : false;

        define('IS_AMP', $checkAmp);
        if (IS_AMP)
            define('TEMPLATE', 'web.layout-amp');
        else
            define('TEMPLATE', 'web.layout');

        define('IS_MOBILE', isMobile());

        $this->getBannerData();
    }

    function getBannerData(){
        $bannerData = Banner::all()->toArray();
        if (!empty($bannerData)) {
            $bannerData = array_group_by($bannerData, function ($a){
                return $a['slug'];
            });
            config(['app.banner' => $bannerData]);
        }
    }
}
