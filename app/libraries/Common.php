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