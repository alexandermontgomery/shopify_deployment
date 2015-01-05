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
		       	.when('/environments', {
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

shopifyDeploymentApp.controller('shopifyDeploymentDeployments', ['$scope', 'ShopifyDeployment', '$interval', function($scope, ShopifyDeployment, $interval){
    $scope.shopifyDeployment = ShopifyDeployment;
    $scope.syncSummary = {};
    $scope.timeoutProm = null;
    $scope.creatingBuild = false;
    ShopifyDeployment.getEnvironments();
    ShopifyDeployment.getSyncSummary(function(resp){
    	$scope.syncSummary = resp;
    });

    $scope.creatBuild = function(){
    	$scope.creatingBuild = true;
    	ShopifyDeployment.createBuild();
    	$scope.timeoutProm = $interval(function(){
    		ShopifyDeployment.getSyncSummary(function(resp){
		    	$scope.syncSummary = resp;
		    	if($scope.syncSummary.files_to_sync.length == 0){
		    		$scope.creatingBuild = false;
		    		$interval.cancel($scope.timeoutProm);
		    	}
		    });
    	}, 3000);
    };
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