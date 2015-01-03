<?php

class ShopifyRepo{

	private $dir;

	public function __construct(Shopify $shopify, ShopifyEnvironments $environments){
		$this->shopify = $shopify;
		$this->envs = $environments;
		$this->ensureRepo();
	}

	private function ensureRepo(){
		$this->dir = Config::get('app.repo_path') . '/' . $this->shopify->shop;

		if(!mkdir($dir, 777, TRUE)){
			throw new Exception("Could not make repo for store " . $this->shopify->shop);
		}
	}

}

class ShopifyRepoBranch{

	public function __construct(Shopify $shopify, $env){
		$this->shopify = $shopify;
	}
}