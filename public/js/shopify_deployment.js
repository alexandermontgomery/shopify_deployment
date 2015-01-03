var shopifyDeploymentApp = angular.module('shopifyDeployment', ['ui.bootstrap', 'ngRoute'])
	.config(['$routeProvider', '$locationProvider', 
		function($routeProvider, $locationProvider){
			$routeProvider
				.when('/', {
		          templateUrl: '/html/home.html',
		          controller: 'shopifyDeploymentHome',
		        })
		        .when('/deployments', {
		          templateUrl: '/html/deployments.html',
		          controller: 'shopifyDeploymentDeployments',
		        })
		       	.when('/configs', {
		          templateUrl: '/html/environments.html',
		          controller: 'shopifyEnvironments',
		        });		    
		    $locationProvider.html5Mode(false);
		}]);

shopifyDeploymentApp.filter('num', function() {
    return function(input) {
      return parseInt(input);
    }
});

shopifyDeploymentApp.controller('shopifyDeployment', ['$scope', 'ShopifyDeployment', '$route', '$routeParams', '$location', function($scope, ShopifyDeployment, $route, $routeParams, $location){	
	this.$route = $route;
    this.$location = $location;
    this.$routeParams = $routeParams;
}]);

shopifyDeploymentApp.controller('shopifyDeploymentHome', ['$scope', 'ShopifyDeployment', '$route', '$routeParams', '$location', function($scope, ShopifyDeployment, $route, $routeParams, $location){
	this.$route = $route;
    this.$location = $location;
    this.$routeParams = $routeParams;
}]);

shopifyDeploymentApp.controller('shopifyDeploymentDeployments', ['$scope', 'ShopifyDeployment', '$route', '$routeParams', '$location', function($scope, ShopifyDeployment, $route, $routeParams, $location){
	this.$route = $route;
    this.$location = $location;
    this.$routeParams = $routeParams;
}]);

shopifyDeploymentApp.controller('shopifyEnvironments', ['$scope', 'ShopifyDeployment', '$route', '$routeParams', '$location', function($scope, ShopifyDeployment, $route, $routeParams, $location){
	this.$route = $route;
    this.$location = $location;
    this.$routeParams = $routeParams;
    $scope.shopifyDeployment = ShopifyDeployment;
    $scope.deploymentOptions = [];
    $scope.shopifyDeployment.getShopifyThemes(function(resp){
    	if(angular.isDefined(resp)){
	    	for(var i = 0; i<resp.length; i++){
	    		$scope.deploymentOptions.push({
	    			id : resp[i].id,
	    			name : resp[i].name
	    		});
	    	}
    	}   	
    });
    $scope.shopifyDeployment.getEnvironments();

    $scope.saveThemeConfigurations = function(){
    	$scope.shopifyDeployment.saveEnvironments();
    }
}]);