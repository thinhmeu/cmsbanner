<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*404*/
Route::any('/404.html', 'RouterController@not_found');

Route::get('/allBannerRandom', 'HomeController@allBannerRandom');
/*Author*/
Route::get('/author/{slug}{checkAmp}','AuthorController@index')->where(['slug' => '[\d\w-]+', 'checkAmp' => '(/amp)?'])->middleware('Redirect.301')->middleware('Config.Slug');
Route::get('/author/{slug}/page/{page}{checkAmp}','AuthorController@index')->where(['slug' => '[\d\w-]+', 'page' => '[0-9]+', 'checkAmp' => '(/amp)?'])->middleware('Redirect.301')->middleware('Config.Slug');

/*Rss*/
Route::get('/feed/','RssController@home')->middleware('Config.Slug');

Route::get('/test','HomeController@test');
Route::get('/ajax-load-more-post-amp','RouterController@ajax_load_more_post_amp');
Route::get('/{checkAmp}','HomeController@index')->where(['checkAmp' => '(amp)?'])->middleware('Redirect.301');
Route::get('/{slug}{checkAmp}','RouterController@index')->where(['slug' => '[\d\w-]+', 'checkAmp' => '(/amp)?'])->middleware('Redirect.301')->middleware('Config.Slug');
Route::get('/{slugParent}/{slug}{checkAmp}/','RouterController@category')->where(['slugParent' => '[\d\w-]+', 'slug' => '[\d\w-]+', 'checkAmp' => '(/amp)?'])->middleware('Redirect.301')->middleware('Config.Slug');
Route::post('/rating/rating','RouterController@rating');
/*Sitemap*/
Route::get('/sitemap.xml','SitemapController@index');
Route::get('/sitemap-category.xml','SitemapController@category');
Route::get('/sitemap-news.xml','SitemapController@news');
Route::get('/sitemap-page.xml','SitemapController@page');
Route::get('/sitemap-posts-{year}-{month}.xml','SitemapController@post')->where(['year'=>'\d+', 'month'=>'\d+']);
/*Crawler*/
Route::get('/crawler/{slug}','CrawlerController@index')->where(['slug' => '[\s\S]+']);
/*Other*/
//Route::get('/{slug}', 'RouterController@any')->middleware('Redirect.301');
Route::any('{slug}', 'RouterController@any')->where('slug', '.*')->middleware('Redirect.301');
