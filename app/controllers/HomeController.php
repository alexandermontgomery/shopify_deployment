<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function __construct(Shopify $shopify){
		$this->shopify = $shopify;
	}

	public function showHome()
	{
		return View::make('home');
	}

	public function shopifyAuthorizeEnd(){
		$this->shopify->makeClient();

        // Now, request the token and store it in your session.
        $token = $this->shopify->client->getAccessToken(Input::get('code'));
        $this->shopify->saveToken($token);

        return Redirect::to('home');
	}

	public function shopifyAuthorizeStart(){
    	// Step 1: get the shopname from the user and redirect the user to the
        // shopify authorization page where they can choose to authorize this app
        $this->shopify->makeClient();

        // get the URL to the current page
        $callback = isset($_SERVER['https']) ? 'https://' : 'http://';
        $callback .= $_SERVER['HTTP_HOST'] . '/shopify_authorize/end';

        // redirect to authorize url
        header("Location: " . $this->shopify->client->getAuthorizeUrl('read_themes,write_themes', $callback));
        exit;
	}

}
