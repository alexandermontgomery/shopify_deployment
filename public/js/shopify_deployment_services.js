shopifyDeploymentApp.service('ShopifyDeployment', ["$http", "$rootScope", function($http, $rootScope){
	var ShopifyDeployment = {};
	ShopifyDeployment.configs = {};

	ShopifyDeployment.getShopifyThemes = function(callback){
		if(typeof ShopifyDeployment.shopifyThemes == 'undefined'){
			$http.get('/shopify_themes').success(function(response){
				ShopifyDeployment.shopifyThemes = response;
				callback(ShopifyDeployment.shopifyThemes);
			});
		}
		callback(ShopifyDeployment.shopifyThemes);		
	}

	ShopifyDeployment.saveEnvironments = function(){	
		$http.post('/shopify_environments', ShopifyDeployment.configs).success(function(response){
			console.log(response);
		});
	}

	ShopifyDeployment.getEnvironments = function(callback){
		if(typeof ShopifyDeployment.environments == 'undefined'){
			$http.get('/shopify_environments').success(function(response){
				if(!response.hasOwnProperty('dev')){
					response = {};
				}
				ShopifyDeployment.environments = response;
				if(typeof callback != 'undefined'){
					callback(ShopifyDeployment.environments);
				}				
			});	
		}
		if(typeof callback != 'undefined'){
			callback(ShopifyDeployment.environments);
		}				
	}

	ShopifyDeployment.getSyncSummary = function(callback){
		$http.get('/download_sync_summary').success(function(response){
			callback(response);
		});
	}

	ShopifyDeployment.createBuild = function(){
		$http.get('/download_sync').success(function(resp){

		});
	}

	return ShopifyDeployment;
}]);