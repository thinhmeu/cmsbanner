<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;
use App\Models\Banner;
use Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class BannerController extends ApiController
{
    public function index($siteName){
        $queryVitri = DB::raw("(SELECT banner_site.`slug` FROM banner_site WHERE `type` = 'position' AND id = id_position) AS vitri");
        $queryNumsOfShow = DB::raw("(COALESCE((SELECT nums_of_show FROM banner_numsofshow JOIN banner AS b ON b.`id_website` = banner_numsofshow.`id_website` AND b.`id_position` = banner_numsofshow.`id_position` WHERE b.id_website = banner.`id_website` AND b.id_position = banner.`id_position` LIMIT 1), 1)) AS nums_of_show");
        DB::enableQueryLog();
        $data = Banner::select("link", "target", "rel", "image", "alt", "width", "height", "banner.type", "content", $queryVitri, $queryNumsOfShow)
            ->join("banner_site", "id_website", "=", "banner_site.id")
            ->where("banner_site.slug", $siteName)
            ->where("status", 1)
            ->whereBetween(DB::raw("NOW()"), [DB::raw("COALESCE(start_date, NOW())"), DB::raw("COALESCE(end_date, NOW())")])
            ->orderBy("order", "asc")->get()->toArray();

        $tmp = [];
        foreach ($data as $item){
            $tmp[$item['vitri']][] = $item;
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

        return preg_replace_callback("#\[([\w\d-]+)\]#", function ($m) use ($dataKeyDomain){
            $key = $m[1];
            if (isset($dataKeyDomain[$key])){
                return $dataKeyDomain[$key];
            } else {
                return '#';
            }
        }, $content);

    }
    private function getKeyDomain(){
        $keyCache = __FUNCTION__;
        $data = Cache::get($keyCache);
        if (empty($data)){
            $craw = Http::get("https://r.bmbmic88.com//read.php?action=SEARCH_DOMAIN&brand=domains&u=mkt1");
            if ($craw->status() != 200)
                return [];
            preg_match_all("#([\w\d-]+)\",\"name\":\"([^\"]+)#", $craw->body(), $m);
            $data = array_combine($m[1], $m[2]);
            foreach ($data as &$i)
                $i = "https:\/\/{$i}";
            Cache::put($keyCache, $data, 5*60);
        }
        return $data;
    }

    private function insertFromCsv(){
        $arrImage = [
            'go88' => 'https://i.imgur.com/oX5f6JL.gif',
            'hitclub' => 'https://i.imgur.com/DVW2eIf.gif',
            'sunwin' => 'https://placehold.co/728x90',
            'rikvip' => 'https://i.imgur.com/EjaYMyh.gif',
            'win79' => 'https://i.imgur.com/jm0Lth0.gif',
            'zowin' => 'https://i.imgur.com/FhuDRnv.gif',
            'gemwin' => 'https://placehold.co/728x90',
            'nhatvip' => 'https://i.imgur.com/727nVuO.gif',
            '789club' => 'https://i.imgur.com/a7s2cpD.gif',
            'manclub' => 'https://i.imgur.com/AyyqTJh.gif',
            'five88' => 'https://i.imgur.com/VJ6kgOM.gif',
            'sin88' => 'https://i.imgur.com/24TM3Lm.gif',
            'zbet' => 'https://i.imgur.com/G5Qse4H.gif',
            'may88' => 'https://i.imgur.com/gGdwiLO.gif',
            'sv88' => 'https://i.imgur.com/0VCvJeS.gif',
            'sky88' => 'https://i.imgur.com/Y84P9Ls.gif',
            'red88' => 'https://i.imgur.com/xUZOPZZ.gif',
            'xo88' => 'https://i.imgur.com/xeSS1eU.gif',
            'uk88' => 'https://i.imgur.com/5MY0Zcc.gif',
            'mibet' => 'https://placehold.co/728x90',
            '8live' => 'https://i.imgur.com/vcPw64k.gif',
            'debet' => 'https://placehold.co/728x90'
        ];
        $arrPositionHead = [9, 21];
        $arrPositionCatfish = [13, 14];
        $idHeadPc = 9;
        $idCatfishPc = 14;
        $idWeb = ['conggamenohu' => 94, 'choigametaixiu' => 95, 'gamebaionlinevip' => 96, 'choilodeonline' => 92, 'tylekeotructiep' => 93];

        $list = file_get_contents("link.csv");
        preg_match_all("#[^,\s]+#", $list, $m);
        $m = array_chunk($m[0], 3);
        $save = [];
        foreach ($m as $stt => $i){
            if (in_array($stt%10, [0,1,2,3,4,5])){
                $id_position = $idHeadPc;
            } else {
                $id_position = $idCatfishPc;
            }

            $save[] = [
                'id_website' => $idWeb[$i[0]],
                'id_position' => $id_position,
                'title' => $i[1],
                'slug' => $i[1],
                'link' => "[{$i[2]}]",
                'target' => '_blank',
                'rel' => 'nofollow',
                'image' => $arrImage[$i[1]],
                'alt' => $i[1],
                'width' => 928,
                'height' => 90,
                'status' => 1,
                'order' => $stt,
                'type' => 'default'
            ];
        }
        Banner::insert($save);
        dd($save);
        dd($m);
    }
}
