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
}
