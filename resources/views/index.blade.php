<!DOCTYPE html>
<html lang="ja" ng-app="app">
	<head>
		<meta charset="utf-8">
		<title>CodeGarage</title>
		<meta name="description" content="{{$description}}">
		<meta name="keywords" content="ソースコードの倉庫のことで、MBEDのC言語からAndroidのJava, ゲームのCPPなどソースコードであれば何でもありです。">

		<link rel="icon" type="image/png" href="http://codegarage.edisonthk.com/img/icon@57x57.png" />
		<base href="/_p/">

		<!-- Open Graph Protocol -->
		<!-- Google Plus && Facebook -->
		<meta property="og:type" content="website" />
		<meta property="og:title" content="{{$title}}" />
		<meta property="og:url" content="{{$url}}" />
		<meta property="og:image" content="http://codegarage.edisonthk.com/img/icon@114x114.png" />
		<meta property="og:description" content="{{$description}}" />

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:title" content="{{$title}}" />
		<meta name="twitter:description" content="{{$description}}" />
		<meta name="twitter:image" content="http://codegarage.edisonthk.com/img/icon@114x114.png" />
		<meta name="twitter:url" content="{{$url}}" />


		<link rel="stylesheet" type="text/css" href="css/normalize.min.css">
		<link rel="stylesheet" type="text/css" href="css/global.css">

		<link rel="stylesheet" type="text/css" href="bower_components/components-font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="bower_components/ng-tags-input/ng-tags-input.css">
		<!-- <link rel="stylesheet" type="text/css" href="bower_components/google-code-prettify/bin/prettify.min.css"> -->
		<link rel="stylesheet" type="text/css" href="bower_components/google-code-prettify/styles/desert.css">
		<!-- <link rel="stylesheet" type="text/css" href="bower_components/angular-social/angular-social.css"> -->
		<link rel="stylesheet" type="text/css" href="bower_components/angularjs-toaster/toaster.min.css">
	</head>
	<body>
		
		<div ng-controller="LoaderController" ng-init="loaderInit()">
			<div ng-if="!loaded" class="splash-screen">
				<div class="center">
					<h1>CodeGarage</h1>
					<h3>ローディング <span>...</span></h3>
				</div>
			</div>
			<ng-include ng-controller="MyController" onload="onloadedEvent()" src="'components/app.html'"></ng-include>
		</div>

		<script type="text/javascript" src="bower_components/angular/angular.min.js"></script>
		<script type="text/javascript" src="bower_components/angular-ui-router/release/angular-ui-router.min.js"></script>
		<script type="text/javascript" src="bower_components/angular-google-analytics/dist/angular-google-analytics.min.js"></script>
		<script type="text/javascript" src="bower_components/angular-resource/angular-resource.min.js"></script>
		<script type="text/javascript" src="bower_components/showdown/src/showdown.js"></script>
		<script type="text/javascript" src="bower_components/ng-tags-input/ng-tags-input.js"></script>
		<script type="text/javascript" src="bower_components/angular-social/src/scripts/00-directive.js"></script>
		<script type="text/javascript" src="bower_components/angular-social/src/scripts/02-facebook.js"></script>
		<script type="text/javascript" src="bower_components/angular-social/src/scripts/03-twitter.js"></script>
		<script type="text/javascript" src="bower_components/angular-social/src/scripts/04-google-plus.js"></script>
		<script type="text/javascript" src="bower_components/google-code-prettify/bin/prettify.min.js"></script>
		<script type="text/javascript" src="bower_components/angular-animate/angular-animate.min.js"></script>
		<script type="text/javascript" src="bower_components/angular-sanitize/angular-sanitize.js"></script>
		<script type="text/javascript" src="bower_components/angularjs-toaster/toaster.min.js"></script>
		
		<script type="text/javascript" src="js/app.js"></script>
		<script type="text/javascript" src="js/controllers.js"></script>
		<script type="text/javascript" src="js/directives.js"></script>
		<script type="text/javascript" src="js/services.js"></script>
		
	</body>
</html>