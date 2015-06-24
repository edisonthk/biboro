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
        .msg {
            position: relative;
            text-align: center;
        }
	  	.msg h3 {
	  		display: inline-block;
	  		text-align: center;
	  		font-size: 16px;
            position: relative;
	  	}
        .msg h3.login {
            left: -15px;
        }
	  	.msg #dots {
            position: absolute;
            display: inline-block;
            width: 30px;
            height: 30px;
	  		font-size: 150%;
            top: 3px;
            left: calc(50% + 40px);
            text-align: left;
            letter-spacing: 4px;
	  	}
	  	.center {
	  		margin-top: 140px;
	  	}
	</style>
</head>
<body>
	<div class="center">
		<h1>CodeGarage</h1>
        @if($action == "login")
        <div class="msg">
            <h3 class="login">ログイン中 </h3><div id="dots"></div>
        </div>
		
        @elseif($action == "success")
        <div class="msg">
            <h3>ログイン成功！ <br/> リダイレクトします</h3>
        </div>
        @endif
	</div>
	<script>
		var num_dots = 0;
		var dot_element = document.getElementById("dots");
        if(dot_element) {
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
        }
		
		
	</script>
    @if($action == "login")
        <script>
            setTimeout(function(){
                window.location = 'https://accounts.google.com/o/oauth2/auth?scope=' +
              'https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email&' +
              'redirect_uri={{env('GOOGLE_REDIRECT')}}&'+
              'response_type=code&' +
              'client_id='+encodeURI('{{env('GOOGLE_CLIENT_ID')}}')+'&' +
              @if(!is_null($user))
              'login_hint='+encodeURI('{{$user->google_id}}')+'&'+
              'include_granted_scopes=true&'+
              @endif
              'access_type=online';
            },1000);
        </script>
    @elseif($action == "success")
    <script>
        setTimeout(function() {
            window.location = '{{$requested_uri}}';
        }, 2000);
    </script>
    @endif
</body>
</html>