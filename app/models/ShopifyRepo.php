<?php

use Gitonomy\Git\Repository;

class ShopifyRepo{

	private $dir;
	private $repo;

	public function __construct(Shopify $shopify, ShopifyEnvironments $environments){
		$this->shopify = $shopify;
		$this->envs = $environments;
		$this->ensureRepo();
	}

	private function ensureRepo(){
		$this->dir = Config::get('app.repo_path') . '/' . $this->shopify->shop;

		if(!dir($this->dir) && !mkdir($this->dir, 777, TRUE)){
			throw new Exception("Could not make repo for store " . $this->shopify->shop);
		}

		$this->repo = new Repository($this->dir);
	}

}

class ShopifyRepoBranch{

	public function __construct(Shopify $shopify, $env){
		$this->shopify = $shopify;
	}
}