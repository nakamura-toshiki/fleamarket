@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="auth-form">
    <h2 class="auth-form__heading">会員登録</h2>
    <div class="auth-form__inner">
        <form class="auth-form__form" action="/register" method="post">
        @csrf
            <div class="auth-form__group">
                <label class="auth-form__label" for="name">ユーザー名</label>
                <input class="auth-form__input" type="text" name="name" id="name" value="{{ old('name') }}">
                <p class="auth-form__error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="auth-form__group">
                <label class="auth-form__label" for="email">メールアドレス</label>
                <input class="auth-form__input" type="mail" name="email" id="email" value="{{ old('email') }}">
                <p class="auth-form__error-message">
                    @error('email')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="auth-form__group">
                <label class="auth-form__label" for="password">パスワード</label>
                <input class="auth-form__input" type="password" name="password" id="password">
                <p class="auth-form__error-message">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="auth-form__group">
                <label class="auth-form__label" for="password_confirmation">確認用パスワード</label>
                <input class="auth-form__input" type="password" name="password_confirmation" id="password_confirmation">
                <p class="auth-form__error-message">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <input class="auth-form__btn" type="submit" value="登録する">
        </form>
        <a class="auth-link" href="/login">ログインはこちら</a>
    </div>
</div>
@endsection