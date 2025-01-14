@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<header class="verify-header">
    <img class="logo-img" src="{{ asset('storage/logo.svg') }}" alt="ロゴ">
</header>
<div class="verify-content">
    <p class="verify-content__txt">
        ご登録ありがとうございます！<br>
        登録を完了するために、メール内のリンクをクリックしてください。
    </p>
    <form method="POST" action="{{ route('verification.send') }}">
    @csrf
        <button class="mail-button" type="submit">確認メールを再送信</button>
    </form>
</div>
@endsection