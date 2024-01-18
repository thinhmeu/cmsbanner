<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Route;

class AdminUrl{
    public static function getUrlBannerSite($type, $id){
        return \route("getUrlBannerSite", [$type, $id]);
    }
    public static function getUrlBannerSiteDelete($id){
        return \route("getUrlBannerSiteDelete", [$id]);
    }

}
