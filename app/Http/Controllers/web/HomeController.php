<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\WebController;
use App\Models\BoxNews;
use App\Models\Category;
use App\Models\Post;
use App\Models\TopGame;

class HomeController extends WebController
{
    public function index(){
        $oneItem = BoxNews::find(1);
        if (!empty($oneItem)) {
            $oneItem = json_decode($oneItem->data);
            foreach ($oneItem as $key => $value) {
                //$oneItem[$key]->item = Post::where('status', 1)->where('category_id', $value->id)->orderBy('id', 'desc')->limit(7)->get();
                $oneItem[$key]->item = Post::join('post_category', 'post_category.post_id', '=', 'post.id')->where('post.status', 1)->where('post_category.category_id', $value->id)->orderBy('post.id', 'desc')->limit(10)->get();
            }
        }

        $data['category_homepage'] = $oneItem;

        // $data['post_in_home'] = Post::with(['Category' => function($q) use ($value){
        //     $q->where('category.id', '=', 117);
        // }])->where('status', 1)->orderBy('id', 'desc')->limit(10)->get();
        // dd($data['post_in_home']);

        $oneItem = BoxNews::find(2);
        if (!empty($oneItem)) {
            $oneItem = json_decode($oneItem->data);
            foreach ($oneItem as $key => $value) {
                $oneItem[$key]->item = Post::join('post_category', 'post_category.post_id', '=', 'post.id')->where('post.status', 1)->where('post_category.category_id', $value->id)->orderBy('post.id', 'desc')->limit(3)->get();
            }
        }
        $data['sidebar_homepage'] = $oneItem;

        $data['game_bai'] = TopGame::with('post')->where('type', 1)->orderBy('id', 'ASC')->get();

        $data['seo_data'] = initSeoData();
        $data['heading_title'] = $data['seo_data']['meta_title'];
        return view('web.home.index', $data);
    }

    public function allBannerRandom() {
        $list_api = [
            'XOSO888' => ['url' => 'https://xoso888.vip/Api_request/getAllBanner', 'delimiter' => '_', 'media_prefix' => 'https://xoso888.vip/public/media'],
            'XOSOPLUS' => ['url' => 'https://xosoplus.win/api/getAllBanner', 'delimiter' => '_', 'media_prefix' => 'https://xosoplus.win/public/media'],
            'XEMKEO' => ['url' => 'https://xemkeo.top/vskc/api/getAllBanner', 'delimiter' => 'x', 'media_prefix' => 'https://xemkeo.top'],
            'NHACAIUYTIN360' => ['url' => 'https://nhacaiuytin360.net/api/getAllBanner', 'delimiter' => 'x', 'media_prefix' => 'https://nhacaiuytin360.net/'],
            'TRUYEN24' => ['url' => 'https://truyen24.info/api/getAllBanner/', 'delimiter' => '_', 'media_prefix' => 'https://truyen24.info'],
            'BONGDA365' => ['url' => 'https://bongda365.biz/api/getAllBanner', 'delimiter' => '_', 'media_prefix' => 'https://upload.bongda365.biz'],
            'TUVISO' => ['url' => 'https://tuviso.com/api/getAllBanner/', 'delimiter' => '_', 'media_prefix' => 'https://tuviso.com/public/media'],
        ];
        if (isset($_GET['domain'])) {
            $list_api = [$_GET['domain'] => $list_api[$_GET['domain']]];
        }
        foreach ($list_api as $domain => $api) {
            $param_url = $domain == 'XEMKEO' ? 'link' : 'url';
            $banner = getDataAPI($api['url']);
            $banner = json_decode($banner, 1);
            echo '<table border="1" cellspacing="0" cellpadding="10" style="margin-top: 10px">';
            echo '<tr><th colspan="10">'.$domain.'</th></tr>';
            echo '<tr><th colspan="2">928x90</th><th colspan="2">728x90</th><th colspan="2">400x100</th><th colspan="2">300x250</th><th colspan="2">130x300</th></tr>';
            for ($i = 0; $i < 100; $i++) {
                if (isset($_GET['show_image'])) {
                    $img_928 = '<img src="'.$api['media_prefix'].($banner['928'.$api['delimiter'].'90'][$i]['image'] ?? '').'">';
                    $img_728 = '<img src="'.$api['media_prefix'].($banner['728'.$api['delimiter'].'90'][$i]['image'] ?? '').'">';
                    $img_400 = '<img src="'.$api['media_prefix'].($banner['400'.$api['delimiter'].'100'][$i]['image'] ?? '').'">';
                    $img_300 = '<img src="'.$api['media_prefix'].($banner['300'.$api['delimiter'].'250'][$i]['image'] ?? '').'">';
                    $img_130 = '<img src="'.$api['media_prefix'].($banner['130'.$api['delimiter'].'300'][$i]['image'] ?? '').'">';
                } else {
                    $img_928 = $img_728 = $img_400 = $img_300 = $img_130 = '';
                }
                echo '<tr>
<td>'.$img_928.'</td><td>'.($banner['928'.$api['delimiter'].'90'][$i][$param_url] ?? '').'</td>
<td>'.$img_728.'</td><td>'.($banner['728'.$api['delimiter'].'90'][$i][$param_url] ?? '').'</td>
<td>'.$img_400.'</td><td>'.($banner['400'.$api['delimiter'].'100'][$i][$param_url] ?? '').'</td>
<td>'.$img_300.'</td><td>'.($banner['300'.$api['delimiter'].'250'][$i][$param_url] ?? '').'</td>
<td>'.$img_130.'</td><td>'.($banner['130'.$api['delimiter'].'300'][$i][$param_url] ?? '').'</td>
</tr>';
                if (empty($banner['928'.$api['delimiter'].'90'][$i]['image']) && empty($banner['728'.$api['delimiter'].'90'][$i]['image']) && empty($banner['400'.$api['delimiter'].'100'][$i]['image']) && empty($banner['300'.$api['delimiter'].'250'][$i]['image']) && empty($banner['130'.$api['delimiter'].'300'][$i]['image'])) break;
            }
            echo '</table>';
        }
        echo '<style>td{word-break: break-all;} table {width: 100%} img {width: 150px}</style>';
    }
}
