<!DOCTYPE html>
<html>
<head>
    <title>BIBORO Extension</title>
    <meta charset="UTF-8">
    <link href="//fonts.googleapis.com/css?family=Roboto:400,100,400italic,700italic,700" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/extension/css/prettify.min.css">
    <link rel="stylesheet" type="text/css" href="/extension/css/github-theme.prettify.css">
    <link rel="stylesheet" type="text/css" href="/extension/css/common.css">
</head>
<body>
    <div class="container">
        <div class="topbar clearfix">
            <h3 class="logo"><img src="/img/logo_blue.png"></h3>
            <div class="user">
                {{$user["name"]}}<br/>
                <small><a class="signout" href="{{action("AuthController@getLogout")}}">違うアカウントでログイン</a></small>
            </div>
        </div>
        
        <div class="tabs clearfix">
            @foreach($workbooks as $wb)
            <div class="pane" index="{{$wb->id}}">{{$wb->title}}</div>
            @endforeach
        </div>
    
        <div class="editor-content clearfix">
            <div class="col-left">
                <form action="{{action("ExtensionController@store")}}" method="post">
                    <input class="form-control" type="text" name="title" placeholder="スニペットについてすこし語りましょう">
                    <small></small>
<textarea class="form-control" name="content">
```
{{$snippet}}
```
</textarea>
                    <input class="form-control" type="hidden" name="workbook">
                    {{-- <input class="form-control" name="tags[]" value="C">
                    <input class="form-control" name="tags[]" value="数値">
                    <input class="form-control" name="tags[]" value="math"> --}}
                    <input class="form-control" type="hidden" name="ref" value="{{$ref}}">
                    <button class="btn" type="submit">コピー <span class="meta"></span>+S</button>
                </form>
            </div>
            <div class="col-left md">
                <div>
                    <div id="md-title" class="title"></div>
                    <div id="md-content"></div>
                </div>
            </div>
        </div>

        <div class="tooltips errMessages">
            <ul>
                <li>fsdfsdfs</li>
                <li>fsdfsdfs</li>
            </ul>
        </div>
            
    </div>
    <script src="/extension/js/jquery-1.11.3.js"></script>
    <script src="/extension/js/prettify.min.js"></script>
    <script src="/extension/js/marked.js"></script>
    <script src="/extension/js/extension.js"></script>
</body>
</html>