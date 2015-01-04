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

		if(!file_exists($this->dir) && !mkdir($this->dir, 0755, TRUE)){
			throw new Exception("Could not make repo for store " . $this->shopify->shop);
		}
		
		// See if the repo is initiated
		try{
			$this->repo = new Repository($this->dir);
			$this->repo->run('status');
		}catch(Exception $e){
			Log::info("Initializing repo for shop " . $this->shopify->shop);
			$this->repo = Gitonomy\Git\Admin::init($this->dir, false);
			file_put_contents($this->dir . '/README.md', 'hello');
			$this->repo->run('config', array('user.name', $this->shopify->shop));
			$this->repo->run('add', array('README.md'));
			$this->repo->run('commit', array('-m "Initial Commit"'));
		}
	}

	public function getBranch($env){
		return new ShopifyRepoBranch($this->shopify, $this->repo, $env);
	}

	public function addAndCommitAll($message){
		// $wc = $this->repo->getWorkingCopy();
		// $diff = $wc->getDiffStaged();
		// $files = $diff->getFiles();
		// if(count($files) > 0){
		// 	$this->repo->run('add', array('-A .'));
		// 	$this->repo->run('commit', array('-m "' . $message . '"'));
		// }
		try{
			$this->repo->run('add', array('-A', '.'));
			$this->repo->run('commit', array('-m "' . $message . '"'));			
		}catch(Exception $e){
			Log::info("Nothing to commit for shop " . $this->shopify->shop);
		}

	}

}

class ShopifyRepoBranch{

	public function __construct(Shopify $shopify, Repository $repo, $env){
		$this->shopify = $shopify;
		$this->repo = $repo;
		$this->env = $env;
		$this->branch_name = $env->env . '_' . $env->theme_id;
		$this->wc = $this->repo->getWorkingCopy();
		$this->ensureBranch();
	}

	private function ensureBranch(){
		$ref = $this->repo->getReferences();
		if(!$ref->hasBranch($this->branch_name)){
			$this->wc->checkout('master', $this->branch_name);
		}
		else{
			$this->wc->checkout($this->branch_name);
		}
	}

	public function getShopifyAssetsFormatted(){
		$assets = array();
		$shopify_assets = $this->shopify->getAssets($this->env->theme_id);
		foreach($shopify_assets as $asset){
			$assets[$asset['key']] = array(
				'path' => $asset['key'],
				'last_mod' => strtotime($asset['updated_at'])
			);
			if(isset($asset['public_url'])){
				$assets[$asset['key']]['public_url'] = $asset['public_url'];
			}
		}
		return $assets;
	}

	public function getLocalRepoAssetsFormatted($repo_tree = NULL, $parent = NULL){
		$head = $this->repo->getHeadCommit();
		if(!isset($repo_tree)){
			$repo_tree = $head->getTree();			
		}

		if(strlen($parent)){
			$parent .= '/';
		}

		$repo_files = $repo_tree->getEntries();
		$assets = array();
		foreach($repo_files as $path => $repo_file){
			$full_path = $parent . $path;
			list($mode, $entry) = $repo_file;
			if($entry instanceof Gitonomy\Git\Tree){
				$assets = array_merge($this->getLocalRepoAssetsFormatted($entry, $full_path), $assets);
			}
			else{
				$local_mod = $head->getLastModification($full_path);
				$local_mod_ts = $local_mod->getAuthorDate()->getTimestamp();
				$assets[$full_path] = array(
					'path' => $full_path,
					'last_mod' => $local_mod_ts
				);
			}
		}
		return $assets;
	}

	public function assetDownloadSummary(){
		
		// In the case of a download - the source assets are the files in Shopify
		$source_assets = $this->getShopifyAssetsFormatted();

		// In the case of a download - the destination assets are the files in the local repo
		$destination_assets = $this->getLocalRepoAssetsFormatted();
		return $this->assetSyncSummary($source_assets, $destination_assets);
	}

	private function getAbsolutePath($asset){
		return $this->repo->getPath() . '/' . $asset['path'];
	}

	private function ensureDirectoryExists($path){
		$info = pathinfo($path);
		if(!file_exists($info['dirname']) && !mkdir($info['dirname'], 0755, TRUE)){
			throw new Exception("Could not make directory to save asset " . $path);
		}
	}

	public function downloadAsset($asset){
		$save_location = $this->getAbsolutePath($asset);
		$this->ensureDirectoryExists($save_location);
		if(isset($asset['public_url'])){
			file_put_contents($save_location, fopen($asset['public_url'], 'r'));		
		}
		else{
			$asset_data = $this->shopify->getAsset($this->env->theme_id, $asset['path']);
			if(isset($asset_data['value'])){
				file_put_contents($save_location, $asset_data['value']);
			}
			else if(isset($asset_data['attachment'])){
				file_put_contents($save_location, $asset_data['attachment']);
			}
		}
	}

	private function assetSyncSummary($source_files, $destination_files){		
		$summary = array(
			'synced' => 0,
			'total' => count($source_files),
			'files_to_sync' => array()
		);
		foreach($source_files as $path => $source_asset){
			$destination_asset = isset($destination_files[$path]) ? $destination_files[$path] : NULL;
			if(!isset($destination_asset['last_mod']) || $destination_asset['last_mod'] < $source_asset['last_mod']){
				$summary['files_to_sync'][] = $source_asset;
			}
			else{
				$summary['synced'] += 1;
			}
		}
		return $summary;
	}
}