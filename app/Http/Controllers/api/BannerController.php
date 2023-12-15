<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class BannerController extends ApiController
{
    public function index($siteName){
        $data = DB::select("SELECT link, target, rel, image, alt, width, height, banner.type, content, (SELECT banner_site.`slug` FROM banner_site WHERE `type` = 'position' AND id = id_position) AS vitri
FROM banner JOIN banner_site ON id_website = banner_site.id
WHERE banner_site.`slug` = '$siteName' AND `status` = 1 AND NOW() BETWEEN IFNULL(start_date,'1900-01-01') AND IFNULL(end_date,NOW()) ORDER BY banner.`order`");
        $tmp = [];
        foreach ($data as $item){
            $tmp[$item->vitri][] = $item;
        }
        ksort($tmp);
        $tmp = json_encode($tmp);

        $tmp = $this->replaceKeyDomain($tmp);

        return response($tmp)->withHeaders([
            'Content-Type' => 'application/json',
            'X-Header-One' => 'Header Value',
            'X-Header-Two' => 'Header Value',
        ]);
    /*echo json_encode($tmp);*/
    }
    private function replaceKeyDomain($content){
        $dataKeyDomain = $this->getKeyDomain();

        return preg_replace_callback("#\[[\w\-\.]*?\]#", function ($m) use ($dataKeyDomain){
            $key = trim($m[0], '[]');
            return $dataKeyDomain[$key] ?? '#';
        }, $content);

    }
    private function getKeyDomain(){
        $keyCache = __FUNCTION__;
        $data = Cache::get($keyCache);
        if (empty($data)){
            $craw = Http::get("https://r.bmbmic88.com//read.php?action=SEARCH_DOMAIN&brand=domains&u=mkt1");
            if ($craw->status() != 200)
                return [];
            $data = $craw->json();
            $tmp = [];
            foreach ($data as $i)
                $tmp[$i['key']] = "https://{$i['name']}";
            Cache::put($keyCache, $tmp, 5*60);
            $data = $tmp;
        }
        return $data;
    }
}
