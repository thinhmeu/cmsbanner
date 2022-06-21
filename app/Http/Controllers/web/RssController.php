<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\WebController;
use App\Models\Category;
use App\Models\Post;

class RssController extends WebController
{
    public function index() {
        $data['categories'] = Category::all();
        return view('web.rss.index', $data);
    }

    function home() {
        $posts = Post::where(['status' => 1, ['displayed_time', '<=', Post::raw('NOW()')]])->orderBy('displayed_time', 'DESC')->limit(50)->get();
        $rss = '<?xml version="1.0" encoding="utf-8" ?>';
        $rss .= '<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/"
                    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
                    xmlns:dc="http://purl.org/dc/elements/1.1/"
                    xmlns:atom="http://www.w3.org/2005/Atom"
                    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
                    xmlns:slash="http://purl.org/rss/1.0/modules/slash/">';
        $rss .= '<channel>';
        $rss .= '<title>'. strip_quotes(getSiteSetting('site_title')) .'</title>';
        $rss .= '<atom:link href="'.getUrlLink('/feed', 0).'" rel="self" type="application/rss+xml" />';
        $rss .= '<link>'.getUrlLink('/', 0).'</link>';
        $rss .= '<description>'. strip_quotes(getSiteSetting('site_description')) .'</description>';
        $rss .= '<lastBuildDate>'.date("D, d M Y H:i:s O").'</lastBuildDate>';
        $rss .= '<language>vi</language>';
        $rss .= '<sy:updatePeriod>hourly</sy:updatePeriod><sy:updateFrequency>1</sy:updateFrequency>';
        $rss .= '<image>';
        $rss .= '<url>'.url('web/images/favicon.png').'</url>';
        $rss .= '<title>'. strip_quotes(getSiteSetting('site_title')) .'</title>';
        $rss .= '<link>'.getUrlLink('/', 0).'</link>';
        $rss .= '<width>64</width><height>64</height>';
        $rss .= '</image>';
        foreach ($posts as $post) {
            $rss .= '<item>';
            $rss .= '<title>'. strip_quotes($post->title) .'</title>';
            $rss .= '<link>'.getUrlPost($post, 0).'</link>';
            $rss .= '<dc:creator><![CDATA[ADMIN]]></dc:creator>';
            $rss .= '<pubDate>'.date("D, d M Y H:i:s O", strtotime($post->displayed_time)).'</pubDate>';
            $rss .= '<guid>'.getUrlPost($post, 0).'</guid>';
            $rss .= '<description><![CDATA[ <a href="'.getUrlPost($post, 0).'"><img width="180px" border="0" src="'.get_thumbnail($post->thumbnail).'" align="left" hspace="5" /></a><div>'.html_entity_decode($post->description, ENT_QUOTES, 'UTF-8').'</div> ]]></description>';
            $rss .= '<content:encoded><![CDATA[ '.html_entity_decode(content_rss_replace($post->content), ENT_QUOTES, 'UTF-8').' ]]></content:encoded>';
            $rss .= '</item>';
        }
        $rss .= '</channel>';
        $rss .= '</rss>';
        header("Content-Type: text/xml; charset=utf-8");
        echo $rss;
    }

    public function detail($slug) {
        $category = Category::whereSlug($slug)->first();
        $posts = Post::where(['status' => 1, 'category_id' => $category->id, ['displayed_time', '<=', Post::raw('NOW()')]])->orderBy('displayed_time', 'DESC')->limit(50)->get();
        $rss = '<?xml version="1.0" encoding="utf-8" ?>';
        $rss .= '<rss version="2.0"
        xmlns:content="http://purl.org/rss/1.0/modules/content/"
        xmlns:wfw="http://wellformedweb.org/CommentAPI/"
        xmlns:dc="http://purl.org/dc/elements/1.1/"
        xmlns:atom="http://www.w3.org/2005/Atom"
        xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
        xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
        >';

        //sitemap category
        $rss .= '<channel>';
        $rss .= '<title>'.$category->title.' | Doithuongthecao</title>';
        $rss .= '<atom:link href="'.getUrlLink("/rss/".$category->slug.".rss").'" rel="self" type="application/rss+xml" />';
        $rss .= '<link>'.getUrlCate($category, 0).'</link>';
        $rss .= '<description>'.strip_tags(html_entity_decode($category->description, ENT_QUOTES, 'UTF-8')).'</description>';
        $rss .= '<lastBuildDate>'.date("D, d M Y H:i:s O").'</lastBuildDate>';
        $rss .= '<language>vi-vn</language>';
        $rss .= '<sy:updatePeriod>hourly</sy:updatePeriod><sy:updateFrequency>1</sy:updateFrequency>';
        $rss .= '<image>';
        $rss .= '<url>'.url(getSiteSetting('site_logo')).'</url>';
        $rss .= '<title>'.$category->title.' | Doithuongthecao</title>';
        $rss .= '<link>'.getUrlCate($category, 0).'</link>';
        $rss .= '<width>144</width>';
        $rss .= '<height>50</height>';
        $rss .= '</image>';
        $rss .= '<pubDate>'.date("D, d M Y H:i:s O").'</pubDate>';
        $rss .= '<docs>'.getUrlLink('/feed').'</docs>';
        foreach ($posts as $post) {
            $rss .= '<item>';
            $rss .= '<title><![CDATA[ '.$post->title.' ]]></title>';
            $rss .= '<description><![CDATA[ <a href="'.getUrlPost($post, 0).'"><img width="180px" border="0" src="'.get_thumbnail($post->thumbnail).'" align="left" hspace="5" /></a><div>'.html_entity_decode($post->description, ENT_QUOTES, 'UTF-8').'</div> ]]></description>';
            $rss .= '<content:encoded><![CDATA[ '.html_entity_decode(content_rss_replace($post->content), ENT_QUOTES, 'UTF-8').' ]]></content:encoded>';
            $rss .= '<link>'.getUrlPost($post, 0).'</link>';
            $rss .= '<pubDate>'.date("D, d M Y H:i:s O", strtotime($post->displayed_time)).'</pubDate>';
            $rss .= '<guid>'.getUrlPost($post, 0).'</guid>';
            $rss .= '<atom:link href="'.getUrlPost($post, 0).'" rel="self" type="application/rss+xml" />';
            $rss .= '</item>';
        }
        $rss .= '</channel>';
        $rss .= '</rss>';
        header("Content-Type: text/xml; charset=utf-8");
        echo $rss;
    }
}
