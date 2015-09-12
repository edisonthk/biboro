@extends('auth.layouts')

@section('title')
アカウント登録
@endsection

@section('content')
<h1 class="title"><span>REGISTER</span></h1>
<div class="form register-form">

    <form method="POST" action="{{action('AuthController@postRegister')}}" novalidate>
    {!! csrf_field() !!}

    <div class="form-errors">
        <ul></ul>
    </div>

    <div class="form-group">
        <div class="form-label">
            <i class="fa fa-user"></i>
        </div>
        <div class="form-component">
            <input type="text" name="name" value="{{ old('name') }}" placeholder="{{Lang::get("messages.label.name")}}">
        </div>
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

    <div class="form-group">
        <div class="form-label">
            <i class="fa fa-unlock"></i>
        </div>
        <div class="form-component">
            <input type="password" name="password_confirmation" id="password" placeholder="{{Lang::get("validation.attributes.validation_password")}}">
        </div>
    </div>
    <div class="margin-vertical">
        <div class="form-group form-submit">
            <div class="form-component">
                <button class="btn" type="submit"><i class="fa fa-upload"></i> 登録</button>
            </div>
        </div>
    </div>
</form>
@endsection