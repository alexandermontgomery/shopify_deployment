<?php

class Shopify{

	private $token;

	public function __construct($shop, $key, $secret){
		$this->key = $key;
		$this->secret = $secret;
		$this->shop = $shop;
	}

	public function getToken(){
		if(!isset($this->shop)){
			throw new ShopifyException("Shop not set yet");
		}

		if(isset($this->token)){
			return $this->token;
		}

		$res = DB::select("SELECT * FROM shopify_tokens WHERE shop = ?", array($this->shop));	
		$this->token = isset($res[0]->token) ? $res[0]->token : NULL;
		return $this->token;
	}

	public function revokeToken(){
		if(!isset($this->shop)){
			throw new ShopifyException("Shop not set yet");
		}
		$res = DB::delete("DELETE FROM shopify_tokens WHERE shop = ?", array($this->shop));
	}

	public function saveToken($token){
		DB::insert("INSERT INTO shopify_tokens (shop, token) VALUES(?, ?) ON DUPLICATE KEY UPDATE token=VALUES(token)", array($this->shop, $token));
	}

	public function makeClient($token = ''){
		// Not enough information
		if(!isset($this->shop)){
			throw new ShopifyException("Shop or token not known");
		}
		$this->client = new ShopifyClient($this->shop, $token, $this->key, $this->secret);
	}

	public function getShopInfo(){
		$this->shop_info = $this->client->call('GET', '/admin/shop.json');
	}

	public function listThemes(){
		$this->themes = $this->client->call('GET', '/admin/themes.json');
		return $this->themes;
	}
}

class ShopifyException extends Exception { }