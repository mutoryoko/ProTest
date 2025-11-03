@extends('layouts.default')

@section('title', '取引チャット')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/chat.css')}}" />
@endsection

@section('content')
    <div class="content">
        <div class="aside">
            <h2>その他の取引</h2>
        </div>

        <div class="chat__wrapper">
            <div class="chat__header">
                <h1 class="chat__title">【ユーザー名さん】との取引画面</h1>
                <form class="chat-finished__form" action="" method="POST">
                    @csrf
                    <button class="chat-finished__button">取引を完了する</button>
                </form>
            </div>
            <div class="chat__item-info">
                <div class="item-image__wrapper">
                    <img src="" alt="商品画像">
                </div>
                <h2 class="item-name">商品名</h2>
                <p class="item-price">商品価格</p>
            </div>
            <div class="chat-area">
                <form action="" method="POST">
                    @csrf
                    <input type="text" placeholder="取引メッセージを記入してください">
                    <label class="file__upload-btn">
                        画像を選択する
                        <input class="file-input" type="file">
                    </label>
                    <button>
                        <img src="{{ asset('materials/send-chat.png') }}" alt="送信">
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection