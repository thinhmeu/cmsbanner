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
//Login
Route::any('/login','UserController@login')->name('login');

Route::group(['middleware' => ['auth', 'checkPermission']], function () {
    /*Home*/
    Route::get('/home','HomeController@index');
    /*Ajax*/
    Route::post('/ajax/changePassword','AjaxController@changePassword');
    Route::post('/ajax/loadTag','AjaxController@loadTag');
    Route::post('/ajax/loadCategory','AjaxController@loadCategory');
    Route::post('/ajax/ajax_search_post','AjaxController@ajax_search_post');
    Route::post('/ajax/ajax_duplicate_row/{id}','AjaxController@ajax_duplicate_row')->where(['id' => '[0-9]+']);
    /*Category*/
    Route::get('/category','CategoryController@index');
    Route::any('/category/update','CategoryController@update');
    Route::any('/category/update/{id}','CategoryController@update')->where(['id' => '[0-9]+']);
    Route::any('/category/delete/{id}','CategoryController@delete')->where(['id' => '[0-9]+']);
    /*User*/
    Route::any('/user','UserController@index');
    Route::any('/user/update','UserController@update');
    Route::any('/user/update/{id}','UserController@update')->where(['id' => '[0-9]+']);
    Route::any('/user/delete/{id}','UserController@delete')->where(['id' => '[0-9]+']);
    Route::any('/user/logout','UserController@logout');
    /*Tag*/
    Route::get('/tag','TagController@index');
    Route::any('/tag/update','TagController@update');
    Route::any('/tag/update/{id}','TagController@update')->where(['id' => '[0-9]+']);
    Route::any('/tag/delete/{id}','TagController@delete')->where(['id' => '[0-9]+']);
    /*Group Permission*/
    Route::get('/group','GroupController@index');
    Route::any('/group/update','GroupController@update');
    Route::any('/group/update/{id}','GroupController@update')->where(['id' => '[0-9]+']);
    Route::any('/group/delete/{id}','GroupController@delete')->where(['id' => '[0-9]+']);
    /*Site setting*/
    Route::any('/site_setting/update','Site_SettingController@update');

    /*Banner*/
    Route::get('banner','BannerController@index')->name("getUrlBannerList");
    Route::any('banner/{id?}','BannerController@updateOrInsertBanner')->where(['id' => '\d+'])
        ->name("getUrlBannerUpdate");
    Route::get('/banner/delete/{id}','BannerController@delete')->where(['id' => '\d+'])
        ->name("getUrlBannerDelete");
    Route::get('/banner/duplicate/{id}','BannerController@duplicate')->where(['id' => '[0-9]+'])
        ->name("getUrlBannerDuplicate");

    Route::get('banner/{type}/{id?}','BannerController@site')
        ->where(['type' => 'website|position', 'id' => '\d+'])
        ->name("getUrlBannerSite");
    Route::get('/banner/deleteSite/{id}','BannerController@deleteSite')
        ->where(['id' => '[0-9]+'])->name("getUrlBannerSiteDelete");
});
