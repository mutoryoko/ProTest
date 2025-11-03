@extends('layouts.default')

@section('title', '取引チャット')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/chat.css')}}" />
@endsection

@section('content')
    <div class="content">
        <div class="aside">
            <p class="aside__title">その他の取引</p>
        </div>

        <div class="chat__wrapper">
            <div class="chat__header">
                <div class="user-info">
                    {{-- @if($comment->user->profile && $comment->user->profile->user_image)
                        <div class="user-image__wrapper">
                            <img src="{{ asset('storage/'.$comment->user->profile->user_image) }}" alt="ユーザーアイコン" class="user-image" />
                        </div>
                    @else --}}
                        <div class="user-image__wrapper">
                            <img src="{{ asset('storage/profile-images/no-image.png')}}" alt="no-image" class="user-image">
                        </div>
                    {{-- @endif --}}
                    <h1 class="chat__title">【ユーザー名さん】との取引画面</h1>
                </div>
                <form action="" method="POST">
                    @csrf
                    <button class="chat-finished__button">取引を完了する</button>
                </form>
            </div>
            <div class="item-info">
                <div class="item-image__wrapper">
                    <img src="" alt="商品画像">
                </div>
                <div>
                    <h2 class="item-name">商品名</h2>
                    <p class="item-price">
                        <span class="yen">¥</span>
                        商品価格
                    </p>
                </div>
            </div>
            <div class="chat-area">

                {{-- チャット内容 --}}

                {{-- メッセージ入力欄 --}}
                <form class="chat__form" action="" method="POST">
                    @csrf
                    <input class="chat__input" type="text" placeholder="取引メッセージを記入してください">
                    <label class="file__upload-btn">
                        画像を追加
                        <input class="file-input" type="file">
                    </label>
                    <button class="chat__submit">
                        <img class="send-icon" src="{{ asset('materials/send-chat.png') }}" alt="送信">
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection