<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class BannerController extends ApiController
{
    public function index($siteName){
        $data = DB::table('banner')->join('banner_site', 'id_website', '=', 'banner_site.id')
            ->select('banner.link, banner.target, banner.rel, banner.image, banner.alt, banner.width, banner.height, banner_site.slug')
            ->where('banner_site.slug', '=', $siteName)
            ->get();
        dd($data);
    }
}
