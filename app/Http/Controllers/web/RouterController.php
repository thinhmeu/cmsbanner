<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\WebController;
use App\Models\Category;
use App\Models\Post;
use App\Models\Page;
use App\Models\User;
use App\Models\Rating;
use App\Models\TopGame;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Cookie;

class RouterController extends WebController
{
    public function index($slug) {
        $oneItem = Category::getBySlug($slug);
        if (!empty($oneItem)) {
            return $this->category($slug, $slug);
        } else {
            $oneItem = Page::getBySlug($slug);
            if (!empty($oneItem)) {
                return $this->page($slug);
            } else {
                return $this->post($slug);
            }
        }
    }

    public function post($slug) {
        $data['page'] = $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $oneItem = Post::getBySlug($slug);
        if (empty($oneItem)) {
            return Redirect::to(url('404.html'));
        }
        if ($oneItem->status == 0 || strtotime($oneItem->displayed_time) > time())
            return Redirect::to(url('404.html'));

        $oneItem->content = $this->convertContent($oneItem->content, ['meta_title' => $oneItem->meta_title]);
        $data['oneItem'] = $oneItem;
        $data['category'] = $category = Category::getById($oneItem->category_id);
        if (!empty($category->parent_id)) {
            $oneParent = Category::find($category->parent_id);
        }
        if (!empty($oneItem->optional))
            $data['optional'] = json_decode($oneItem->optional);
        $data['limit'] = $limit  = 12;
        $data['related_post'] = Post::where(['status' => 1, 'category_id' => $oneItem->category_id, ['displayed_time', '<=', Post::raw('NOW()')], ['id', '<>', $oneItem->id]])->orderBy('displayed_time', 'DESC')->offset(($page-1)*$limit)->limit($limit)->get();
        $data['seo_data'] = initSeoData($oneItem, 'post');
        $data['seo_data']['fb_cmt'] = 1;

        $breadCrumb = [];
        if (!empty($oneParent))
            $breadCrumb[] = [
                'name' => $oneParent->title,
                'item' => getUrlCate($oneParent),
                'schema' => false,
                'show' => true
            ];
        if (!empty($category)) $breadCrumb[] = [
            'name' => $category->title,
            'item' => getUrlCate($category),
            'schema' => false,
            'show' => true
        ];
        $breadCrumb[] = [
            'name' => $oneItem->title,
            'item' => getUrlPost($oneItem),
            'schema' => true,
            'show' => false
        ];

        $data['breadCrumb'] = $breadCrumb;
        $data['user'] = User::where('id',$oneItem->user_id)->first();
        $data['schema'] = getSchemaBreadCrumb($breadCrumb);
        return view('web.post.index', $data);
    }

    public function page($slug) {
        $oneItem = Page::getBySlug($slug);
        if (empty($oneItem)) return Redirect::to(url('404.html'));

        $oneItem->content = $this->convertContent($oneItem->content);
        $data['oneItem'] = $oneItem;
        $data['seo_data'] = initSeoData($oneItem, 'page');
        $data['heading_title'] = $data['seo_data']['meta_title'];

        return view('web.page.index', $data);
    }

    private function convertContent($content, $extra_data = []) {
        if (!empty($extra_data['meta_title'])) {
            $count = 1;
            $meta_title = strip_quotes($extra_data['meta_title']);
            $content = preg_replace_callback('<img.*?(alt=[\'\"].*?[\'\"]).*?\/>', function ($match) use (&$count, &$meta_title){
                if (!empty($match[1])) {
                    $match[0] = str_replace($match[1], "alt='$meta_title - Ảnh $count'", $match[0]);
                } else {
                    $match[0] = str_replace('/>', " alt='$meta_title - Ảnh $count' />", $match[0]);
                }
                $count++;
                return $match[0];
            }, $content);
        }
        if (!IS_AMP){
            $content = str_replace('<img ', '<img loading="lazy"', $content);
            $content = str_replace('<iframe ', '<iframe loading="lazy"', $content);

            /*aspect ratio iframe*/
            preg_match_all('/<iframe[\s\S]*?<\/iframe>/', $content, $match);
            if (!empty($match[0]))
                foreach ($match[0] as $item){
                    preg_match('/width=[\'"](.*?)[\'"]/', $item, $width);
                    if (!empty($width[1]))
                        $width = $width[1];

                    preg_match('/height=[\'"](.*?)[\'"]/', $item, $height);
                    if (!empty($height[1]))
                        $height = $height[1];
                    $aspectRatio = $height/$width * 100;

                    $parentIframe = '<div class="ratio d-block mw-100 mx-auto" style="width: '.$width.'px; --bs-aspect-ratio: '.$aspectRatio.'%">'.$item.'</div>';

                    $content = str_replace($item, $parentIframe, $content);
                }
        } else {
            $content = str_replace('<iframe ', '<amp-iframe layout="responsive" sandbox="allow-scripts allow-same-origin allow-popups" ', $content);
            $content = str_replace('</iframe>', '</amp-iframe>', $content);
        }
        return $content;
    }

    public function category($slugParent, $slug) {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $oneItem = Category::getBySlug($slug);
        if (empty($oneItem))
            return Redirect::to(url('404.html'));

        $oneParent = Category::getOneParent($oneItem);
        if ($oneParent->slug !== $slugParent)
            return Redirect::to(url('404.html'));

        $data['oneItem'] = $oneItem;

        if($slug == 'game-bai-doi-thuong'){
            $data['cse'] = '<div class="gcse-search"></div>';
        }

        $data['seo_data'] = initSeoData($oneItem,'category');
        $data['heading_title'] = $data['seo_data']['meta_title'];

        $data['limit'] = $limit = 16;
        $data['page'] = $page;

        $data['post'] = Post::where(['status' => 1, 'category_id' => $oneItem->id, ['displayed_time', '<=', Post::raw('NOW()')]])
            ->orderBy('displayed_time', 'DESC')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->get();

        $data['top_game'] = TopGame::with('post')->where('category_id', $oneItem->id)->orderBy('id', 'ASC')->get();

        unset($oneParent);
        if (!empty($oneItem->parent_id)) {
            $oneParent = Category::find($oneItem->parent_id);
        }
        $breadCrumb = [];
        if (!empty($oneParent))
            $breadCrumb[] = [
                'name' => $oneParent->title,
                'item' => getUrlCate($oneParent),
                'schema' => true,
                'show' => true
            ];
        $breadCrumb[] = [
            'name' => $oneItem->title,
            'item' => getUrlCate($oneItem),
            'schema' => true,
            'show' => true
        ];
        $data['breadCrumb'] = $breadCrumb;

        $data['schema'] = getSchemaBreadCrumb($breadCrumb);

        return view('web.category.index', $data);
    }

    public function rating() {
        turnOnAjaxAmp();
        $post = Request::post();

        if (!Cookie::get('rating_'.$post['url'])) {
            Cookie::queue('rating_'.$post['url'], $post['star']);
            $post['count'] = 1;
            $post['avg_rating'] = $post['star'];
            $post['sum_rating'] = $post['star'];
            $rating = Rating::whereUrl($post['url'])->first();
            if (!empty($rating)) {
                $post['count'] = $rating->count + 1;
                $post['sum_rating'] = $rating->sum_rating + $post['star'];
                $post['avg_rating'] = $post['sum_rating'] / $post['count'];
            }
            unset($post['star']);
            Rating::updateOrInsert(['url' => $post['url']], $post);

            $response = initRatingData($post['url']);

            $response['message'] = 'Vote thành công!';

            return Response::json($response);
        }
    }

    public function ajax_load_more_post_amp(){
        turnOnAjaxAmp();
        $dataPost = $_GET;

        $category_id = $dataPost['category_id'] ?? die();
        $limit = $dataPost['limit'] ?? die();
        $page = $dataPost['page'] ?? die();

        $data = Post::where(['status' => 1, 'category_id' => $category_id, ['displayed_time', '<=', Post::raw('NOW()')]])
            ->orderBy('displayed_time', 'DESC')
            ->offset(($page-1)*$limit)
            ->limit($limit + 1)
            ->get()->toArray();

        foreach ($data as &$a){
            $cateItem = Category::getById($a['category_id']);
            $a['category_slug'] = getUrlCate($cateItem);
            $a['category_title'] = $cateItem->title;

            $a['description'] = !empty($a['description']) ? $a['description'] : get_limit_content($a['content'], 200);
            $a['slug'] = getUrlPost($a, 1);
        }

        $dataReturn = array_splice($data, 0, $limit);
        $checkLoadMore = count($data);

        $next = '';
        if ($checkLoadMore){
            $next = url("/ajax-load-more-post-amp?category_id=$category_id&limit=$limit&page=".++$page);
//            $next = "//doithuongthecao.test/ajax-load-more-post-amp?category_id=$category_id&limit=$limit&page=".++$page;
        }

        return Response::json(['items' => $dataReturn, 'next' => $next]);
    }

    public function any() {
        return Redirect::to(url('404.html'));
    }

    public function not_found() {
        abort(404);
    }
}
