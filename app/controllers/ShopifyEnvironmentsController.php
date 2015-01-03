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
			$envs_formatted[$env->env] = $env->theme_id;
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
		$this->configs->save($data_formatted);
	}

	public function show($env){
		$info = $this->envs->get($this->shopify->shop, $env);
		$repo = App::make('ShopifyRepo');
		return $info;
	}
}