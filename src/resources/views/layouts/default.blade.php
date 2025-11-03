<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    @yield('css')
    @livewireStyles
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <a href="{{ route('index') }}"><img class="header__logo" src="{{ asset('materials/logo.svg') }}" alt="ロゴ" /></a>
            <form class="search-form" method="GET">
                <input class="search-form__input" type="text" name="search" placeholder="なにをお探しですか？" value="{{ request('search') }}"/>
                @if(request('tab') === 'mylist')
                    <input type="hidden" name="tab" value="mylist">
                @endif
            </form>
            <nav class="header__nav">
                <ul class="auth-user__function">
                    @auth
                        <li class="header__nav--item auth-btn">
                            <form action="{{ route('logout') }}" method="POST">
                            @csrf
                                <button class="logout__btn" type="submit">ログアウト</button>
                            </form>
                        </li>
                    @endauth
                    @guest
                        <li class="header__nav--item auth-btn">
                            <a class="login__btn" href="{{ route('login')}}">ログイン</a>
                        </li>
                    @endguest
                    <li class="header__nav--item">
                        <a class="mypage__btn" href="{{ route('mypage.index') }}">マイページ</a>
                    </li>
                    <li class="header__nav--item">
                        <a class="sell-items__btn" href="{{ route('sellForm') }}">出品</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
    @livewireScripts
</body>
</html>