<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>flea market</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
    @yield('script')
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <div class="header-inner">
                <a class="logo" href="{{ route('index') }}">
                    <img class="logo-img" src="{{ asset('storage/logo.svg') }}" alt="ロゴ">
                </a>
                <form class="search-form" action="{{ route('index') }}" method="get" id="search-form">
                @csrf
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <input type="text" name="name" placeholder="何をお探しですか？" value="{{ request('name') }}">
                </form>
                <div class="link">
                    @auth
                        <form class="logout-form" action="/logout" method="post">
                        @csrf
                            <input class="logout-link" type="submit" value="ログアウト">
                        </form>
                    @else
                        <div class="login-button">
                            <a class="login-link" href="/login">ログイン</a>
                        </div>
                    @endauth
                    <div class="profile-button">
                        <a class="profile-link" href="{{ route('mypage') }}">マイページ</a>
                    </div>
                    <div class="sell-button">
                        <a class="sell-link" href="{{ route('sell') }}">出品</a>
                    </div>
                </div>
            </div>
        </header>
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>

</html>