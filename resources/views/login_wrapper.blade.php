<!doctype html>
<html lang="jp">
<head>
  	<meta charset="utf-8">
	<title></title>
	<meta name="description" content="">
	<style type="text/css">
	    html, body {
	        min-width: 100%;
	    	height: 100%;
	    	min-height: 100%;
	    	padding: 0;
	    	margin: 0;
	    	font-family: 'ヒラギノ角ゴ Pro W3', 'Hiragino Kaku Gothic Pro', メイリオ, Meiryo, sans-serif;
	  	}
	  	h1, h3{
	  		font-weight: normal;
	  	}
	  	h1 {
	  		text-align: center;
	  		margin-bottom: 0;
	  		color: rgb(22, 109, 244);
	  	}
	  	h3 {
	  		display: block;
	  		width: 110px;
	  		margin: 0 auto;
	  		font-size: 16px;
	  	}
	  	h3 #dots {
	  		font-size: 150%;
	  	}
	  	.center {
	  		margin-top: 140px;
	  	}
	</style>
</head>
<body>
	<div class="center">
		<h1>CodeGarage</h1>
		<h3>ログイン中 <span id="dots"></span></h3>
	</div>
	<script>
		var num_dots = 0;
		var dot_element = document.getElementById("dots");
		setInterval(function() {
			if(num_dots > 3) {
				num_dots = 0;
			}

			var _t = "";
			for (var i = 0; i < num_dots; i++) {
				_t += ".";
			};
			dot_element.innerHTML = _t;

			num_dots ++;
		}, 400);

		var hostname = window.location.hostname;
		if(hostname == 'localhost') {
			hostname += ':8000';
		}

		setTimeout(function(){
			window.location = 'https://accounts.google.com/o/oauth2/auth?scope=' +
	      'https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email&' +
	      'redirect_uri=http://'+hostname+'/account/oauth2callback&'+
	      'response_type=code&' +
	      'client_id='+encodeURI('754486540313-kg9j4k03tnn0s9d9c29gfkt88qqki6tq.apps.googleusercontent.com')+'&' +
	      'access_type=online';
		},1000);
		
	</script>
</body>
</html>