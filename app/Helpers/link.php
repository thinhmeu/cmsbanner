<?php

use App\Models\Category;

function getUrlPost($item, $is_amp = IS_AMP){
    $item = (object) $item;
    $slug = $item->slug;
    if ($is_amp)
        $slug .= "/amp";
    return url($slug).'/';
}
function getUrlStaticPage($item, $is_amp = 0){
    $slug = $item->slug;
    if ($is_amp)
        $slug .= "/amp";
    return url($slug).'/';
}
function getUrlCate($item, $is_amp = IS_AMP){
    $slug = '';
    if (!empty($item->parent_id)) {
        $oneParent = Category::find($item->parent_id);
        $slug .= $oneParent->slug . '/';
    }
    $slug .= "$item->slug";
    if ($is_amp)
        $slug .= "/amp";
    return url($slug).'/';
}
function getUrlTag($item, $is_amp = 0){
    $slug = "$item->slug-t$item->id";
    if ($is_amp)
        $slug .= "/amp";
    return url($slug).'/';
}
function getUrlPage($page) {
    $parts = parse_url($_SERVER['REQUEST_URI']);
    if (isset($parts['query'])) {
        parse_str($parts['query'], $query);
    }
    $query['page'] = $page;
    return $parts['path'].'?'.http_build_query($query);
}
function getUrlLink($slug, $is_amp = ''){
    if (!$is_amp)
        $is_amp = defined('IS_AMP') ? IS_AMP : 0;
    if (substr($slug, -1) != '/') $slug .= '/';
    if ($is_amp) $slug .= "amp";
    return url($slug).'/';
}
function getUrlAuthor($item, $is_amp = IS_AMP){
    $slug = "author/$item->author";
    if ($is_amp)
        $slug .= "/amp";
    return url($slug).'/';
}
function checklinkOut($url) {
    $parse = parse_url($url);
    if (!isset($parse['host'])) return false;
    $host = $parse['host'];
    if ($host == env('DOMAIN')) {
        return false;
    } else {
        return true;
    }
}
function tableOfContent($content) {
    preg_match_all("/<h[23456].*?<\/h[23456]>/",$content,$patt);
    if (empty($patt[0])) return $content;
    $patt2 = $patt[0];
    $index_h2 = 0;
    $index_h3 = 1;
    $danhmuc = "<div class='w-100 border rounded bg-grey6 py-2 px-3 mb-3'>
                    <p class='mb-2 d-flex justify-content-between'>
                        <span class='font-weight-500 font-18'>Mục Lục</span>
                        <a class='icon-toc font-24 border rounded bg-white' data-toggle='collapse' data-target='#collapseExample'></a>
                    </p>";
    $danhmuc .= "<ul class='list-unstyled mb-2 collapse show' id='collapseExample'>";
    foreach ($patt2 as $key=>$item){
        if (strpos($item, '</h2>') !== false) {
            $index_h2++;
            $danhmuc .= "<li class='mb-1'><a class='text-black7 font-14' href='#trick$key' rel='nofollow'>$index_h2. ".strip_tags($item)."</a></li>";
            $index_h3 = 1;
        } else {
            $danhmuc .= "<li class='mb-1 pl-3'><a class='text-black7 font-14' href='#trick$key' rel='nofollow'>$index_h2.$index_h3. ".strip_tags($item)."</a></li>";
            $index_h3++;
        }
        $head = substr($item,0,3);
        $tail = substr($item,3);

        $id = ' id="trick' . $key . '"';
        $content = str_replace($item,$head.$id.$tail,$content);
    }
    $danhmuc .= "</ul></div>";
    $content = "$danhmuc<div class='post-content text-justify font-15'>$content</div>";
    return $content;
}

function add_amp_to_url($body) {
    return preg_replace_callback('#(<a.*?href=")([^"]*)("[^>]*?>)#i', function($match) {
        $url = $match[2];
        if (strpos($url, 'amp/') === false) {
            $url .= 'amp/';
        }
        return $match[1] . $url . $match[3];
    }, $body);
}

if (!function_exists('convertDetailTime')) {
    function convertDetailTime($time = 'now') {
        $dow = getDay($time, 0);
        $date= date("d/m/Y", strtotime($time));
        $time= date("H:i", strtotime($time));
        return "{$dow}, ngày {$date} - {$time}";
    }
}

function getDay($time,$type=0){
    $getday = date('D',strtotime($time));
    $arrayDay = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    $arrayDayVn = ['Thứ Hai','Thứ Ba','Thứ Tư','Thứ Năm','Thứ Sáu','Thứ Bảy','Chủ Nhật'];
    $arrayDayNumber = ['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật'];
    $arrayDayLinkLite = ['thu-2','thu-3','thu-4','thu-5','thu-6','thu-7','chu-nhat'];
    $arrayDayLink = ['t2','t3','t4','t5','t6','t7','cn'];
    if($type == 0){for ($i=0;$i<count($arrayDay);$i++){if($getday == $arrayDay[$i]){return $arrayDayVn[$i];};};};
    if($type == 1){for ($i=0;$i<count($arrayDay);$i++){if($getday == $arrayDay[$i]){return $arrayDayLink[$i];};};};
    if($type == 2){for ($i=0;$i<count($arrayDay);$i++){if($getday == $arrayDay[$i]){return $arrayDayLinkLite[$i];};};};
    if($type == 3){for ($i=0;$i<count($arrayDay);$i++){if($getday == $arrayDay[$i]){return $arrayDayNumber[$i];};};};
    if ($type == 4) {
        $current_type_6 = 'ngày '.date('j',strtotime($time)).' tháng '.date('n',strtotime($time)).' năm '.date('Y',strtotime($time));
        return $current_type_6;
    }
}

