<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Shopify Theme Deployment</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/bootstrap/css/bootstrap-theme.min.css">
	<script type="text/javascript" src="/bootstrap/js/bootstrap.min.js"></script>	
	<script type="text/javascript" src="/js/angular/angular.min.js"></script>
	<script type="text/javascript" src="/js/ui-bootstrap.min.js"></script>
	<script type="text/javascript" src="/js/angular/angular-route.min.js"></script>
	<script type="text/javascript" src="/js/angular/angular-animate.min.js"></script>
	<script type="text/javascript" src="/js/shopify_deployment.js"></script>
	<script type="text/javascript" src="/js/shopify_deployment_services.js"></script>
</head>
<body ng-app="shopifyDeployment" ng-controller="shopifyDeployment">
	<nav class="navbar navbar-inverse">
		<div class="container">
			<div class="navbar-header">
				<a href="#" class="navbar-brand">Shopify Theme Deployment</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li><a href="#deployments">Deployments</a></li>
					<li><a href="#configs">Configure</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div ng-view class="container view-animate">
	</div>
</body>
</html>
