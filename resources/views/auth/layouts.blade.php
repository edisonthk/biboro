<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <title>@yield('title')</title>
    <link href="//fonts.googleapis.com/css?family=Roboto:400,100,400italic,700italic,700" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/css/fa/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/auth.css">
</head>
<body>
    <div class="logo">
        <img src="/img/logo_blue.png" alt="Biboro Logo">
    </div>
    <div class="container-outer">
        <div class="container">
            @yield('content')
        </div>
    </div>

    <script src="/js/jquery-1.11.3.min.js"></script>
    <script src="/js/auth.js"></script>
</body>
</html>