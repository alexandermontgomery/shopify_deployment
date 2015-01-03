<?php

class ShopifyThemeController extends BaseController {

	public function __construct(Shopify $shopify){
		$this->shopify = $shopify;
	}

	public function index(){
		$themes = $this->shopify->listThemes();
		return $themes;
	}
}