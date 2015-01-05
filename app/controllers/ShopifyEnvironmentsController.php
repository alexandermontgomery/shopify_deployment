<?php

class ShopifyEnvironmentsController extends BaseController {
	
	public function __construct(Shopify $shopify, ShopifyEnvironments $envs){
		$this->shopify = $shopify;
		$this->envs = $envs;
	}

	public function index(){
		$envs = $this->envs->getAll($this->shopify->shop);
		$envs_formatted = array();
		foreach($envs as $env){
			$envs_formatted[$env->env] = $env;
		}
		return $envs_formatted;
	}

	public function store(){
		$data = (array)Input::all();
		$data_formatted = array();
		foreach($data as $env => $theme_id){
			$conf = new stdClass();
			$conf->env = $env;
			$conf->shop = $this->shopify->shop;
			$conf->theme_id = $theme_id;
			$data_formatted[] = $conf;
		}
		$this->envs->save($data_formatted);
	}

	public function show($env_name){
		$env = $this->envs->get($this->shopify->shop, $env_name);
		return (array)$env;
	}

	public function downloadSyncSummary(){
		$env = $this->envs->get($this->shopify->shop, 'dev');
		$repo = App::make('ShopifyRepo');
		$summary = $repo->assetDownloadSummary($env->theme_id);
		return $summary;
	}

	public function downloadSync(){
		Queue::push('ShopifySyncController@downloadFromShopify', array('shop' => $this->shopify->shop, 'env' => $this->envs->get($this->shopify->shop, 'dev')));
	}
}