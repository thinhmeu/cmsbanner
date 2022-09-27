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
    Route::get('/banner','BannerController@index');
    Route::get('/banner/{id_website}/{id_position}','BannerController@index')->where(['id_website' => '[0-9]+', 'id_position' => '[0-9]+']);
    Route::any('/banner/update','BannerController@update');
    Route::any('/banner/update/{id_banner}/{id_website}/{id_position}','BannerController@update')->where(['id_banner' => '[0-9]+', 'id_website' => '[0-9]*', 'id_position' => '[0-9]*']);
    Route::any('/banner/delete/{id}','BannerController@delete')->where(['id' => '[0-9]+']);

    Route::get('/banner/site/{type}','BannerController@site')->where(['type' => '[a-z]+']);
    Route::any('/banner/updateSite/{type}','BannerController@updateSite')->where(['type' => '[a-z]+']);
    Route::any('/banner/updateSite/{type}/{id}','BannerController@updateSite')->where(['type' => '[a-z]+', 'id' => '[0-9]+']);
    Route::any('/banner/deleteSite/{id}','BannerController@deleteSite')->where(['id' => '[0-9]+']);

});
