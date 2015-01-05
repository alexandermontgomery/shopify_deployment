<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::any('/shopify_authorize/start', 'HomeController@shopifyAuthorizeStart');
Route::get('/shopify_authorize/end', 'HomeController@shopifyAuthorizeEnd');

Route::get('/home', array('uses' =>'HomeController@showHome'));
Route::group(array('before'=>'auth'), function(){
	Route::get('/download_sync', array('uses' => 'ShopifyEnvironmentsController@downloadSync'));
	Route::get('/download_sync_summary', array('uses' => 'ShopifyEnvironmentsController@downloadSyncSummary'));
	Route::resource('shopify_environments', 'ShopifyEnvironmentsController', array('only' => array('index', 'store', 'show')));
	Route::resource('shopify_themes', 'ShopifyThemeController', array('only' => array('index')));	
});
