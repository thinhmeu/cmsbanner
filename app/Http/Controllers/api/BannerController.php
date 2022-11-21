<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class BannerController extends ApiController
{
    public function index($siteName){
        $data = DB::select("SELECT link, target, rel, image, alt, width, height, (SELECT banner_site.`slug` FROM banner_site WHERE `type` = 'position' AND id = id_position) AS vitri
FROM banner JOIN banner_site ON id_website = banner_site.id
WHERE banner_site.`slug` = '$siteName' AND `status` = 1 AND NOW() BETWEEN IFNULL(start_date,'1900-01-01') AND IFNULL(end_date,NOW()) ORDER BY banner.`order`");
        $tmp = [];
        foreach ($data as $item){
            $tmp[$item->vitri][] = $item;
        }
        ksort($tmp);

        return response($tmp)->withHeaders([
            'Content-Type' => 'application/json',
            'X-Header-One' => 'Header Value',
            'X-Header-Two' => 'Header Value',
        ]);
    /*echo json_encode($tmp);*/
    }
}
