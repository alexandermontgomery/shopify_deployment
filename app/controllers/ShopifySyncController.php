<?php

class ShopifySyncController extends BaseController{
	
	public function downloadFromShopify($job, $data){
		$shop = $data['shop'];
		$env = (object)$data['env'];
		$shopify = Common::getShopify($shop, TRUE);
		App::instance('Shopify', $shopify);
		$repo = App::make('ShopifyRepo');
		$branch = $repo->getBranch($env);
		$summary = $branch->assetDownloadSummary();
		$count = 0;
		foreach($summary['files_to_sync'] as $asset){
			$branch->downloadAsset($asset);
			$count += 1;
			if($count > 5){
				$repo->addAndCommitAll("Comitting files during download sync");
				$count = 0;
			}
		}
		$repo->addAndCommitAll("Comitting files during download sync");
	}

}