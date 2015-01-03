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

	ShopifyDeployment.getEnvironments = function(){
		$http.get('/shopify_environments').success(function(response){
			if(!response.hasOwnProperty('dev')){
				response = {};
			}
			ShopifyDeployment.configs = response;
		});	
	}

	return ShopifyDeployment;
}]);