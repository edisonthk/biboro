@extends('auth.layouts')

@section('title')
ログイン
@endsection

@section('content')
<h1 class="title"><span>LOGIN</span></h1>
<div class="form login-form">
    <form method="POST" action="{{ action('AuthController@postLogin') }}" novalidate>
        {!! csrf_field() !!}

        <div class="form-errors">
            <ul></ul>
        </div>

        <div class="form-group">
            <div class="form-label">
                <i class="fa fa-envelope"></i>
            </div>
            <div class="form-component">
                <input type="email" name="email" value="{{ old('email') }}" placeholder="{{Lang::get("messages.label.email")}}">
            </div>
        </div>

        <div class="form-group">
            <div class="form-label">
                <i class="fa fa-key"></i>
            </div>
            <div class="form-component">
                <input type="password" name="password" id="password" placeholder="{{Lang::get("messages.label.password")}}">
            </div>
        </div>

        <div class="margin-vertical">
            <div class="form-group form-submit">
                <div class="form-component">
                    <button class="btn" type="submit"><i class="fa fa-unlock"></i> ログイン</button>
                </div>
            </div>

            <div class="or">
                <span>もしくは</span>
            </div>

            <div class="form-group form-submit">
                <div class="form-component">
                    <a class="btn" href="{{$oauthUrl}}"><i class="fa fa-google-plus"></i> でログイン／登録</a>
                </div>
            </div>
        </div>
    </form>
</div>

<h3 class="register-title">アカウント登録</h3>
<div class="form register-link-form">
    <div class="form-group">
        <a href="{{action('AuthController@getRegister')}}">Googleでログインしたくない？では、こちらのフォームで登録することもできますよ</a>
    </div>
</div>
@endsection