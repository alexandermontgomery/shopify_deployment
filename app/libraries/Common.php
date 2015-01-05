<?php

class Common{
	public static function getShopify($shop, $make_client = FALSE){
		$shopify = new Shopify($shop, Config::get('app.shopify_key'), Config::get('app.shopify_secret'));
		if($make_client){
			$token = $shopify->getToken();
			if(!isset($token)){
				throw new Exception("No token to make ShopifyClient with");
			}
			$shopify->makeClient($token);
		}
		return $shopify;
	}
}

class BuildTitle{

	private $title;

	public function __construct($title){
		$this->title = $title;
	}

	public function getNumber(){
		$num = str_replace(Config::get('app.build_prefix'), '', $this->title);
		return (int)$num;
	}

	public function nextNumber(){
		$next = ((int)$this->getNumber() + 1);
		return $next;
	}

	public function getTitle(){
		return $this->title;
	}

	public function nextTitle(){
		return Config::get('app.build_prefix') . $this->nextNumber();
	}
}