<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\WebController;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use DateTime;

class SitemapController extends WebController
{
    public function index() {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

        //sitemap category
        $sitemap .= '<sitemap>';
        $sitemap .= '<loc>'.url('sitemap-category.xml').'</loc>';
        $sitemap .= '<lastmod>'.date('c',strtotime('-1 hours')).'</lastmod>';
        $sitemap .= '</sitemap>';

        //sitemap news
//        $sitemap .= '<sitemap>';
//        $sitemap .= '<loc>'.url('sitemap-news.xml').'</loc>';
//        $sitemap .= '<lastmod>'.date('c',strtotime('-1 hours')).'</lastmod>';
//        $sitemap .= '</sitemap>';

        //sitemap page
        $page = Page::all();
        if(!$page->isEmpty()){
            $sitemap .= '<sitemap>';
            $sitemap .= '<loc>'.url('sitemap-page.xml').'</loc>';
            $sitemap .= '<lastmod>'.date('c',strtotime('-1 hours')).'</lastmod>';
            $sitemap .= '</sitemap>';
        }

        //sitemap spin lottery

        $d1 = new DateTime('2021-07-01');
        $d2 = new DateTime();
        $interval = $d2->diff($d1);
        $interval->format('%m months');

        $month_diff = 12*$interval->y + $interval->m;

        $curent_month = date('Y-m');
        for($i=0;$i<=$month_diff;++$i){
            $sm_date = date('Y-m',strtotime('-'.$i.' month',strtotime($curent_month)));
            $sm_month = date('m',strtotime('-'.$i.' month',strtotime($curent_month)));
            $sm_year = date('Y',strtotime('-'.$i.' month',strtotime($curent_month)));
            $post = Post::whereYear('displayed_time', '=', $sm_year)->whereMonth('displayed_time', '=', $sm_month)->get();
            //sitemap post
            if(!$post->isEmpty()){
                $sitemap_url = 'sitemap-posts-'.$sm_date.'.xml';
                $sitemap .= '<sitemap>';
                $sitemap .= '<loc>'.url($sitemap_url).'</loc>';
                $sitemap .= '<lastmod>'.date('c',strtotime('-1 hours')).'</lastmod>';
                $sitemap .= '</sitemap>';
            }

        }

        $sitemap .= '</sitemapindex>';
        return response($sitemap, 200, ['Content-Type' => 'application/xml']);
    }

    public function category() {
        $sitemap_category = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap_category .='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

        $categories = Category::all();
        $sitemap_category .= '<url>';
        $sitemap_category .= '<loc>'.url('').'</loc>';
        $sitemap_category .= '<lastmod>'.date('c').'</lastmod>';
        $sitemap_category .= '<changefreq>daily</changefreq>';
        $sitemap_category .= '<priority>1.0</priority>';
        $sitemap_category .= '</url>';

        foreach($categories as $category){
            $sitemap_category .= '<url>';
            $sitemap_category .= '<loc>'.getUrlCate($category, 0).'</loc>';
            $sitemap_category .= '<lastmod>'.date('c').'</lastmod>';
            $sitemap_category .= '<changefreq>daily</changefreq>';
            $sitemap_category .= '<priority>0.8</priority>';
            $sitemap_category .= '</url>';
        }

        $sitemap_category .= '</urlset>';
        header("Content-Type: text/xml; charset=utf-8");
        print_r($sitemap_category);exit;
    }

    public function news() {
        $sitemap = '<?xml version="1.0"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-news/0.9 http://www.google.com/schemas/sitemap-news/0.9/sitemap-news.xsd">';

        $posts = Post::where(['status'=>1])->get();

        foreach($posts as $post){
            $sitemap .= '<url>';
            $sitemap .= '<loc>'.getUrlPost($post, 0).'</loc>';
            $sitemap .= '<changefreq>always</changefreq>';
            $sitemap .= '<priority>0.9</priority>';
            $sitemap .= '<news:news>';
            $sitemap .= '<news:publication>';
            $sitemap .= '<news:name>Doithuongthecao</news:name>';
            $sitemap .= '<news:language>vi</news:language>';
            $sitemap .= '</news:publication>';
            $sitemap .= '<news:publication_date>'.date('c',strtotime($post->created_time)).'</news:publication_date>';
            $sitemap .= '<news:title>'.htmlspecialchars($post->title).'</news:title>';
            $sitemap .= '<news:keywords>'.htmlspecialchars($post->meta_keyword).'</news:keywords>';
            $sitemap .= '</news:news>';
            $sitemap .= '</url>';
        }

        $sitemap .= '</urlset>';
        header("Content-Type: text/xml; charset=utf-8");
        print_r($sitemap);exit;
    }

    public function page() {
        $sitemap_category = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap_category .='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

        $pages = Page::where(['status'=>1])->get();
        foreach($pages as $page){
            $sitemap_category .= '<url>';
            $sitemap_category .= '<loc>'.getUrlStaticPage($page).'</loc>';
            $sitemap_category .= '<lastmod>'.date('c',strtotime($page->created_time)).'</lastmod>';
            $sitemap_category .= '<changefreq>always</changefreq>';
            $sitemap_category .= '<priority>0.8</priority>';
            $sitemap_category .= '</url>';
        }

        $sitemap_category .= '</urlset>';
        header("Content-Type: text/xml; charset=utf-8");
        print_r($sitemap_category);exit;
    }

    public function post($year, $month) {
        $sitemap_posts = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap_posts .='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

        $posts = Post::where(['status'=>1])->where(['is_index'=>1])->whereYear('created_time', '=', $year)
            ->whereMonth('created_time', '=', $month)->get();

        foreach($posts as $post){
            $sitemap_posts .= '<url>';
            $sitemap_posts .= '<loc>'.getUrlPost($post, 0).'</loc>';
            $sitemap_posts .= '<lastmod>'.date('c',strtotime($post->created_time)).'</lastmod>';
            $sitemap_posts .= '<changefreq>always</changefreq>';
            $sitemap_posts .= '<priority>0.6</priority>';
            $sitemap_posts .= '</url>';
        }

        $sitemap_posts .= '</urlset>';
        header("Content-Type: text/xml; charset=utf-8");
        print_r($sitemap_posts);exit;
    }
}
