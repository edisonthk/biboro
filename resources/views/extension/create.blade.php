<!DOCTYPE html>
<html>
<head>
    <title>BIBORO Extension</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/extension/css/common.css">
</head>
<body>
    <div class="container">
        <div class="topbar" class="clearfix">
            <h3 class="title">BIBORO</h3>
            <div class="user">
                {{$user["name"]}}<br/>
                <small><a class="signout" href="{{action("AccountController@getSignout")}}">違うアカウントでログイン</a></small>
            </div>
        </div>
        <form action="{{action("ExtensionController@store")}}" method="post">
            <input class="form-control" type="text" name="title" placeholder="スニペットについてすこし語りましょう">
            <small></small>
<textarea class="form-control" name="content">
```
{{$snippet}}
```
</textarea>
            <input class="form-control" name="workbook">
            <input class="form-control" name="tags[]" value="C">
            <input class="form-control" name="tags[]" value="数値">
            <input class="form-control" name="tags[]" value="math">
            <input class="form-control" type="text" name="ref" value="{{$ref}}">
            <button type="submit">コピー</button>
        </form>
            
        </div>
    </div>
    <script src="/extension/js/jquery-1.11.3.js"></script>
</body>
</html>