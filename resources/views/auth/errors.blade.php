@extends('auth.layouts')

@section('content')
<div class="errors">

    @if($type == App\Http\Controllers\AuthController::TYPE_OAUTH_DENIED_ACCESS)
        <h1>認証失敗！</h1>
        <p>Googleアカウントで登録する以外の選択肢もあります！こちらの<a href="{{action('AuthController@getRegister')}}">フォーム</a>にて、アカウント登録することもできます。</p>
    @elseif($type == App\Http\Controllers\AuthController::TYPE_UNKNOWN_OAUTH_ERROR)
        <h1>連携失敗！</h1>
        <p>申し訳ございません、こちらのアカウント {{$email}} はGoogleと連携することができませんでした</p>
        <p>こちらの<a href="{{action('AuthController@getRegister')}}">フォーム</a>にて、アカウント登録してください。</p>
    @elseif($type == App\Http\Controllers\AuthController::TYPE_REGISTER)
        <img src="">
        <h1>登録完了</h1>
        <p> さん、ご登録ありがとうございます！</p>
        <a href="{{Config::get("app.app_url")}}">こららをクリックし、アプリへリダイレクトします。</a>
    @endif


</div>
@endsection