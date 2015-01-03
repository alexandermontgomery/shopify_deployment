<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	if(Input::get('shop')){
		Session::put('shopify_store', Input::get('shop'));	
	}
	$shop = Session::get('shopify_store');
	$shopify = new Shopify($shop, Config::get('app.shopify_key'), Config::get('app.shopify_secret'));

	// Register a Shopify object
	App::instance('Shopify', $shopify);
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	$shopify = App::make('Shopify');
	$token = $shopify->getToken();
	if(!isset($token)){
		return Redirect::to('shopify_authorize/start');
	}
	$shopify->makeClient($token);
	try{
		$shopify->getShopInfo();		
	} catch(ShopifyApiException $e){
		$headers = $e->getResponseHeaders();
		$status_code = $headers['http_status_code'];
		// Revoke the token and redirect
		if($status_code > 400 && $status_code < 500){
			//$shopify->revokeToken();
			return Redirect::to('shopify_authorize/start');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() !== Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
