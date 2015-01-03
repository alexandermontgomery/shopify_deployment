<?php

class ShopifyEnvironments{

	public function getAll($shop){
		$res = DB::select("SELECT * FROM shopify_environments WHERE shop = ?", array($shop));
		return $res;
	}

	public function get($shop, $env){
		$res = DB::select("SELECT * FROM shopify_environments WHERE shop = ? AND env = ?", array($shop, $env));
		if(isset($res[0])){
			return $res[0];
		}
		return;
	}

	public function save($configs){
		if(empty($configs)){
			return;
		}
		$insert = "INSERT INTO shopify_environments (shop, env, theme_id) VALUES ";
		$values = array();
		$args = array();
		foreach($configs as $config){
			$values[] = '(?,?,?)';			
			$args[] = $config->shop;
			$args[] = $config->env;
			$args[] = $config->theme_id;
		}
		$insert .= implode(',', $values) . " ON DUPLICATE KEY UPDATE theme_id=VALUES(theme_id)";
		DB::insert($insert, $args);
	}
	
}