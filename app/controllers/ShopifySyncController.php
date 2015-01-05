<?php

class ShopifySyncController extends BaseController{
	
	public function downloadFromShopify($job, $data){
		$shop = $data['shop'];
		$env = (object)$data['env'];
		$shopify = Common::getShopify($shop, TRUE);
		App::instance('Shopify', $shopify);
		$repo = App::make('ShopifyRepo');
		$summary = $repo->assetDownloadSummary($env->theme_id);
		$count = 0;
		foreach($summary['files_to_sync'] as $asset){
			$repo->downloadAsset($env->theme_id, $asset);
			$count += 1;
			if($count > 5){
				$repo->addAndCommitAll("Comitting files during download sync");
				$count = 0;
			}
		}
		$repo->addAndCommitAll("Comitting files during download sync");		
		$references = $repo->repo->getReferences();
		$master  = $references->getBranch('master');
		$hash = $master->getCommitHash();
		$build_title = new BuildTitle($env->build_number);		
		$next_build_title = $build_title->nextTitle();
		$references->createTag($next_build_title, $hash);

		$environment = App::make('ShopifyEnvironments');
		$environment->updateBuild($shop, $env->env, $next_build_title);

		$job->delete();
	}

}