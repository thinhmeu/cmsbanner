<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\WebController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class AuthorController extends WebController
{
    public function index($slug, $page=1) {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $oneItem = User::whereAuthor($slug)->first();
        if (empty($oneItem))
            return Redirect::to(getUrlLink("/author/superadmin/"), 301);

        $data['oneItem'] = $oneItem;

        $data['seo_data'] = initSeoData($oneItem);

        $data['limit'] = $limit = 10;
        $data['page'] = $page;

        $data['post'] = Post::where(['status' => 1, 'user_id' => $oneItem->id, ['displayed_time', '<=', Post::raw('NOW()')]])
            ->orderBy('displayed_time', 'DESC')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->get();

        $breadCrumb = [];
        $breadCrumb[] = [
            'name' => $oneItem->username,
            'item' => getUrlAuthor($oneItem),
            'schema' => false,
            'show' => true
        ];
        $data['breadCrumb'] = $breadCrumb;

        $data['schema'] = getSchemaBreadCrumb($breadCrumb);

        return view('web.author.index', $data);
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
            $a['description'] = !empty($a['description']) ? $a['description'] : get_limit_content($a['content'], 200);
            $a['slug'] = getUrlPost($a, 1);
        }

        $dataReturn = array_splice($data, 0, $limit);
        $checkLoadMore = count($data);

        $next = '';
        if ($checkLoadMore){
            $next = url("/ajax-load-more-post-amp?category_id=$category_id&limit=$limit&page=".++$page);
//            $next = "//gamebaitop.test/ajax-load-more-post-amp?category_id=$category_id&limit=$limit&page=".++$page;
        }

        return Response::json(['items' => $dataReturn, 'next' => $next]);
    }
}
