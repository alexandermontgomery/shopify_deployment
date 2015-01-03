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

Route::get('/home', array('before' => 'auth', 'uses' =>'HomeController@showHome'));
Route::any('/shopify_authorize/start', 'HomeController@shopifyAuthorizeStart');
Route::get('/shopify_authorize/end', 'HomeController@shopifyAuthorizeEnd');
Route::group(array('before'=>'auth'), function(){
	Route::resource('shopify_environments', 'ShopifyEnvironmentsController', array('only' => array('index', 'store')));
	Route::resource('shopify_themes', 'ShopifyThemeController', array('only' => array('index')));	
});
