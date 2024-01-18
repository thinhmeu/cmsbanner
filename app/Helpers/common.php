<?php
use Illuminate\Support\Facades\Cookie;
use App\Models\Rating;

function toSlug($doc)
{
    $str = addslashes(html_entity_decode($doc));
    $str = trim($str);
    $str = toNormal($str);
    $str = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
    $str = preg_replace("/( )/", '-', $str);
    $str = str_replace('/', '', $str);
    $str = str_replace("\/", '', $str);
    $str = str_replace("+", "", $str);
    $str = strtolower($str);
    $str = stripslashes($str);
    return trim($str, '-');
}

function toNormal($str)
{
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
    $str = preg_replace("/(đ)/", 'd', $str);
    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
    $str = preg_replace("/(Đ)/", 'D', $str);
    return $str;
}

function get_limit_content($string, $length = 255)
{
    $string = trim(strip_tags($string));
    //$string = strip_tags($string);
    if (strlen($string) > 0) {
        $arr = explode(' ', $string);
        $return = '';
        if (count($arr) > 0) {
            $count = 0;
            if ($arr) foreach ($arr as $str) {
                $count += strlen($str);
                if ($count > $length) {
                    $return .= "...";
                    break;
                }
                $return .= " " . $str;
            }
            $return = closeTags($return);
        }
        //return trim(strip_tags($return));
        return $return;
    } else {
        return '';
    }
}

function closeTags($html){
    preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
    $openEdTags = $result[1];
    preg_match_all('#</([a-z]+)>#iU', $html, $result);
    $closedTags = $result[1];
    $len_opened = count($openEdTags);
    if (count($closedTags) == $len_opened) {
        return $html;
    }
    $openEdTags = array_reverse($openEdTags);
    for ($i = 0; $i < $len_opened; $i++) {
        if (!in_array($openEdTags[$i], $closedTags)) {
            $html .= '</' . $openEdTags[$i] . '>';
        } else {
            unset($closedTags[array_search($openEdTags[$i], $closedTags)]);
        }
    }
    return $html;
}

function getListPermission() {
    return [
        'category' => 'Chuyên mục',
        'post' => 'Bài viết',
        'page' => 'Trang tĩnh',
        'tag' => 'Tag',
        'top_game' => 'Top game',
        'user' => 'Thành viên',
        'group' => 'Nhóm quyền',
        'banner' => 'Banner',
        'site_setting' => 'Cài đặt chung',
        'redirect' => 'Cấu hình Redirect',
        'menu' => 'Cấu hình Menu',
        'box_news' => 'Cấu hình Box News',
    ];
}

function getCurrentController() {
    $controller = class_basename(Route::current()->controller ?? '');
    return strtolower(str_replace('Controller', '', $controller));
}

function getCurrentAction() {
    return class_basename(Route::current()->getActionMethod());
}

function getCurrentParams() {
    return Route::current()->parameters();
}

function getCurrentControllerTitle() {
    $controller = getCurrentController();
    $listPermission = getListPermission();
    return !empty($listPermission[$controller]) ? $listPermission[$controller] : '';
}

function getSiteSetting($key) {
    $value = '';
    if (!empty($key)) {
        $value = \App\Models\SiteSetting::where('setting_key', $key)->first();
    }
    return $value->setting_value;
}

function strip_quotes($str)
{
    return str_replace(array('"', "'"), '', $str);
}

function get_thumbnail($image_url,$width=500,$height=300,$class='img-fluid',$alt='',$layout='responsive', $lazy=true, $resize = true, $path=true){
    if (empty($width)) $width = 500;
    if (empty($height)) $height = 300;
    $source_file = public_path().$image_url;

    $source_file = str_replace('//','/',$source_file);

    $image_name = substr($image_url, 0, strrpos($image_url, '.'));
    $image_name = preg_replace("/\%.{2}/", '-', $image_name);
    $image_ext = substr($image_url, strrpos($image_url, '.'));

    $resize_image_name = $image_name.'-'.$width.'x'.$height.$image_ext;
    $resize_image_file = public_path().'/thumb'.$resize_image_name;
    $resize_image_url = '/thumb'.$resize_image_name;

    if (!empty($resize) && $image_ext != '.gif') {
        if(file_exists($resize_image_file)){
            $img_src = $resize_image_url;
        }else{
            resize_crop_image($width, $height, $source_file, $resize_image_file);
            if(file_exists($resize_image_file)){
                $img_src = $resize_image_url;
            } else {
                $img_src = $image_url;
            }
        }
    } else {
        $img_src = $image_url;
    }
    if ($lazy) {
        $lazy = "loading='lazy'";
    } else {
        $lazy = '';
    }
   if($path){
       $img_src = env('ASSET_URL_IMAGE','').$img_src;
   }
    if (IS_AMP) {
        return "<amp-img class='{$class}' src='{$img_src}' alt='$alt' width='$width' height='$height' layout='{$layout}'></amp-img>";
    } else {
        return "<img $lazy class='{$class}' src='{$img_src}' alt='$alt' width='$width' height='$height' />";
    }
}

function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
    try {
        $source_file = urldecode($source_file);
        $imgSize = getimagesize($source_file);
        $width = $imgSize[0];
        $height = $imgSize[1];
        $mime = $imgSize['mime'];

        switch ($mime) {
            case 'image/gif':
                $image_create = "imagecreatefromgif";
                $image = "imagegif";
                break;

            case 'image/png':
                $image_create = "imagecreatefrompng";
                $image = "imagepng";
                $quality = 7;
                break;

            case 'image/jpeg':
                $image_create = "imagecreatefromjpeg";
                $image = "imagejpeg";
                $quality = 80;
                break;

            default:
                return false;
                break;
        }

        $dst_img = imagecreatetruecolor($max_width, $max_height);
        $src_img = $image_create($source_file);

        $width_new = $height * $max_width / $max_height;
        $height_new = $width * $max_height / $max_width;
        //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
        if ($width_new > $width) {
            //cut point by height
            $h_point = (($height - $height_new) / 2);
            //copy image
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
        } else {
            //cut point by width
            $w_point = (($width - $width_new) / 2);
            imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
        }

        $folderPath = substr($dst_dir, 0, strrpos($dst_dir, '/'));
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        $image($dst_img, $dst_dir, $quality);

        if ($dst_img) imagedestroy($dst_img);
        if ($src_img) imagedestroy($src_img);
    } catch (Exception $e) {

    }
}

function initSeoData($item='', $type='home'){
    switch ($type) {
        case 'category':
            $data_seo = [
                'meta_title' => strip_quotes($item->meta_title),
                'meta_keyword' => !empty($item->meta_keyword) ? $item->meta_keyword : strip_quotes(getSiteSetting('site_keyword')),
                'meta_description' => strip_quotes($item->meta_description),
                'site_image' => $item->thumbnail,
                'canonical' => getUrlCate($item, 0),
                'amphtml' => getUrlCate($item, 1),
                'is_index' => !empty($item->status) ? 'index,follow' : 'noindex,nofollow',
            ];
            break;
        case 'tag':
            $data_seo = [
                'meta_title' => strip_quotes($item->meta_title),
                'meta_keyword' => !empty($item->meta_keyword) ? $item->meta_keyword : strip_quotes(getSiteSetting('site_keyword')),
                'meta_description' => strip_quotes($item->meta_description),
                'site_image' => url(getSiteSetting('site_logo')),
                'canonical' => getUrlTag($item),
                'amphtml' => getUrlTag($item, 1),
                'index' => !empty($item->index) ? 'index,follow' : 'noindex,nofollow',
            ];
            break;
        case 'page':
            $data_seo = [
                'meta_title' => strip_quotes($item->meta_title),
                'meta_keyword' => !empty($item->meta_keyword) ? $item->meta_keyword : strip_quotes(getSiteSetting('site_keyword')),
                'meta_description' => strip_quotes($item->meta_description),
                'site_image' => $item->thumbnail,
                'canonical' => getUrlStaticPage($item),
                'amphtml' => getUrlStaticPage($item, 1),
                'index' => !empty($item->status) ? 'index,follow' : 'noindex,nofollow',
                'published_time' => !empty($item->displayed_time) ? date('Y-m-d\TH:i:s', strtotime($item->displayed_time)) : '',
                'modified_time' => !empty($item->created_time) ? date('Y-m-d\TH:i:s', strtotime($item->created_time)) : '',
                'updated_time' => !empty($item->updated_time) ? date('Y-m-d\TH:i:s', strtotime($item->updated_time)) :''
            ];
            break;
        case 'post':
            $meta_keyword = $item->main_keyword ?? '';
            if (!empty($item->meta_keyword)) $meta_keyword .= ', '.$item->meta_keyword;
            $data_seo = [
                'meta_title' => strip_quotes($item->meta_title),
                'meta_description' => strip_quotes($item->meta_description),
                'meta_keyword' => !empty($meta_keyword) ? $meta_keyword : strip_quotes(getSiteSetting('site_keyword')),
                'site_image' => $item->thumbnail,
                'canonical' => getUrlPost($item, 0),
                'amphtml' => getUrlPost($item, 1),
                'index' => !empty($item->is_index) ? 'index,follow' : 'noindex, nofollow',
                'published_time' => !empty($item->displayed_time) ? date('Y-m-d\TH:i:s', strtotime($item->displayed_time)) : '',
                'modified_time' => !empty($item->created_time) ? date('Y-m-d\TH:i:s', strtotime($item->created_time)) : '',
                'updated_time' => !empty($item->updated_time) ? date('Y-m-d\TH:i:s', strtotime($item->updated_time)) :''
            ];
            break;
        case 'home':
            $data_seo = [
                'meta_title' => strip_quotes(getSiteSetting('site_title')),
                'meta_keyword' => strip_quotes(getSiteSetting('site_keyword')),
                'meta_description' => strip_quotes(getSiteSetting('site_description')),
                'site_image' => url(getSiteSetting('site_logo')),
                'canonical' => str_replace('/amp', '', url(Request::getRequestUri())),
                'amphtml' => getUrlLink('/', 1),
                'index' => 'index,follow',
                'published_time' => '',
                'modified_time' => '',
                'updated_time' => '',
                'site_content' => getSiteSetting('site_content'),
            ];
            break;
        default:
            $data_seo = [
                'meta_title' => strip_quotes(getSiteSetting('site_title')),
                'meta_keyword' => strip_quotes(getSiteSetting('site_keyword')),
                'meta_description' => strip_quotes(getSiteSetting('site_description')),
                'site_image' => url(getSiteSetting('site_logo')),
                'canonical' => url(Request::getRequestUri()),
                'index' => 'index,follow',
                'published_time' => '',
                'modified_time' => '',
                'updated_time' => ''
            ];
            break;
    }
    return $data_seo;
}

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function getDataAPIPost($url, $data = []){
    $resource = curl_init();
    curl_setopt($resource, CURLOPT_URL, $url);
    curl_setopt($resource, CURLOPT_POST, true);
    curl_setopt($resource, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($resource, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($resource,CURLOPT_TIMEOUT,10);

    $response = curl_exec($resource);
    curl_close($resource);
    return $response;
}

function getDataAPI($urlAPI)
{
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL,$urlAPI);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl_handle,CURLOPT_TIMEOUT,5);
    $data = curl_exec($curl_handle);
    curl_close($curl_handle);
    return $data;
}

function getNumberLinkOut($content) {
    preg_match_all('/href="(.*?)"/', $content, $match);
    return count($match[1]);
}

function content_rss_replace($content){
    $content = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $content);
    $content = preg_replace("/\<iframe.*?\>.*?\<\/iframe\>/", "", $content);
    $content = preg_replace("/caption\=['\"].*?['\"]/", "", $content);
    $content = preg_replace("/controls\=['\"].*?['\"]/", "", $content);
    return $content;
}

function init_cms_pagination($page, $pagination){
    $content = '<ul class="pagination">';
    if ($page > 1) $content .= '<li class="page-item">
                                    <a class="page-link" href="' . getUrlPage($page-1) . '">Prev</a>
                                </li>';
    if ($page > 4) $content .= '<li class="page-item">
                                    <a class="page-link" href="' . getUrlPage(1) . '">1</a>
                                </li>
                                <li class="page-item">
                                    <span class="page-link">...</span>
                                </li>';
    for ($i = $page - 3 ; $i <= $page + 3; $i++) {
        if ($i < 1 || $i > $pagination) continue;
        $active = '';
        if ($i == $page) $active = 'active';
        $content .= '<li class="page-item ' . $active . '">
                        <a class="page-link" href="' . getUrlPage($i) . '">' . $i . '</a>
                    </li>';
    }
    if ($page < $pagination - 3) $content .= '<li class="page-item">
                                                <span class="page-link">...</span>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="' . getUrlPage($pagination) . '">' . $pagination . '</a>
                                            </li>';
    $content .= '<li class="page-item">
                    <a class="page-link" href="' . getUrlPage($page+1) . '">Next</a>
                </li>';
    $content .= '</ul>';
    return $content;
}

function viewGameBai($data) {
    return view('web.block._gamebai_home', ['data' => $data]);
}

function getBreadcrumb($breadcrumb){
    $br = "<section class=\"col-12 pl-0 mb-3 font-12 breadcrumb border-bottom pb-1\">
                    <span><a href=\"/\" class=\"text-black6 text-decoration-none\">Trang chủ</a></span>";

    foreach($breadcrumb as $value){
        if ($value['show']) $br .= "<span class=\"text-black3 px-1\">></span>
                <a class=\"text-grey5\" href=\"{$value['item']}\">{$value['name']}</a>";
    }

    $br .= "</section>";
    return $br;
}

function initGameBaiSidebar() {
    $data['game_bai'] = \App\Models\TopGame::with('post')->where('type', 1)->orderBy('id', 'ASC')->get();
    return view('web.block._gamebai_sidebar', $data);
}

function initBoxNewsHomepage($data, $view, $color) {
    return view("web.block.$view", ['post' => $data, 'color' => $color]);
}

function initRatingData($url){
    $rating = Rating::whereUrl($url)->first();
    if (!empty($rating)) {
        $data = [
            'url' => $url,
            'avg' => round($rating->avg_rating, 1, PHP_ROUND_HALF_UP),
            'count' => $rating->count
        ];
    } else {
        $data = [
            'url' => $url,
            'avg' => 5,
            'count' => 5
        ];
        $params = [
            'url' => $url,
            'count' => 5,
            'avg_rating' => 5,
            'sum_rating' => 25
        ];
        Rating::updateOrInsert(['url' => $url], $params);
    }
    if (!empty(Cookie::get('rating_'.$url))) {
        $data['avg'] = Cookie::get('rating_'.$url);
        $data['readonly'] = true;
    }
    return $data;
}

function initRating($url, $showMessage = 0, $title = '', $schema = false) {
    $data = initRatingData($url);
    $data['showMessage'] = $showMessage;
    $data['title'] = $title;
    $data['schema'] = $schema;
    return view('web.block._rating', $data);
}
function turnOnAjaxAmp(){
    header("Content-type: application/json");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Origin: https://doithuongthecao-com.cdn.ampproject.org");
    header("AMP-Access-Control-Allow-Source-Origin: ". URL::to('/'));
    header("Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin");
}

function array_group_by(array $arr, callable $key_selector)
{
    $result = array();
    foreach ($arr as $i) {
        $key = call_user_func($key_selector, $i);
        $result[$key][] = $i;
    }
    return $result;
}

function getBanner($slug){
    $banners = config('app.banner');
    if (empty($banners[$slug])) return;

    $content = $banners[$slug][0]['content'];
    if (in_array($slug, ['popunder', 'popunder-mobile', 'dat-cuoc-pc', 'dat-cuoc-mobile', 'link-xem-live-mobile', 'link-xem-live-pc'])){
        $content = preg_replace('/\/\*[\s\S]*?\*\//', '', $content);
        return strip_tags($content);
    }

    $html = $content;
    if (strpos($html, 'adsbygoogle') !== false && !IS_AMP) return trim($html);
    $html = preg_replace('/\/\*[\s\S]*?\*\//', '', $html);
    preg_match_all('/(<a[\s\S]*?<\/a>)/', $html, $arr);
    if (empty($arr[0])) return;
    $tmp = '';
    foreach ($arr[0] as $index => $item){
        $idBanner = 'banner'.random_int(1, 10000);
        if (IS_AMP){
            $item = preg_replace('/<img(.*?)>/', '<amp-img layout="responsive"$1></amp-img>', $item);
        } else {
//            $item = str_replace('src=', 'loading="lazy" src=', $item);
            $item = str_replace('src="', 'data-src="', $item);
        }
        $tmp .= '<div class="ads-container position-relative mw-100" data-loaded="0" data-position="'.$slug.$index.'" id="'.$idBanner.'">
                    <span class="banner-close d-flex font-12 p-0 text-center position-absolute">
                        <i class="info-icon bg-white"></i>
                        <i class="close-icon bg-white" on="tap:'.$idBanner.'.toggleClass(class=\'d-none\'),adsPopUp.hide" role="button" tabindex="-1"  data-dismiss="modal"></i>
                    </span>
                    <div class="banners">'.$item.'</div>
                </div>';
    }
    return $tmp;
}

function substrwords($text, $maxchar, $end='...') {
    if($text == null || $text == ''){
        return $text;
    }
    if (strlen($text) > $maxchar || $text == '') {
        $words = preg_split('/\s/', $text);
        $output = '';
        $i      = 0;
        while (1) {
            $length = strlen($output)+strlen($words[$i]);
            if ($length > $maxchar) {
                break;
            }
            else {
                $output .= " " . $words[$i];
                ++$i;
            }
        }
        $output .= $end;
    }
    else {
        $output = $text;
    }
    return $output;
}

function getBannerPc($slug){
    if (IS_MOBILE)
        return false;
    return getBanner($slug);
}
function getBannerMobile($slug){
    if (!IS_MOBILE)
        return false;
    return getBanner($slug);
}
?>
