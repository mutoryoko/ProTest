@extends('layouts.default')

@section('title', '取引チャット')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/chat.css')}}" />
@endsection

@section('content')
    <div class="content">
        <div class="aside">
            <p class="aside__title">その他の取引</p>
            @foreach ($otherTransactionItems as $item)
                <a class="aside__item-link" href="{{ route('chat.show', ['transaction' => $item->transaction->id]) }}">{{ $item->item_name }}</a>
            @endforeach
        </div>

        <div class="chat__wrapper">
            <div class="chat__header">
                <div class="user-info">
                    @if($partner->profile && $partner->profile->user_image)
                        <div class="user-image__wrapper">
                            <img src="{{ asset('storage/'.$partner->profile->user_image) }}" alt="ユーザーアイコン" class="user-image" />
                        </div>
                    @else
                        <div class="user-image__wrapper">
                            <img src="{{ asset('storage/profile-images/no-image.png')}}" alt="no-image" class="user-image">
                        </div>
                    @endif
                    <h1 class="chat__title">
                        {{ $partner->name }}さんとの取引画面
                    </h1>
                </div>
                @if(Auth::user()->id === $transaction->buyer_id)
                    <form action="" method="POST">
                        @csrf
                        <button class="chat-finished__button">取引を完了する</button>
                    </form>
                @endif
            </div>
            <div class="item-info">
                <div class="item-image__wrapper">
                    <img class="item-image" src="{{ asset('storage/'.$transaction->item->item_image) }}" alt="商品画像">
                </div>
                <div>
                    <h2 class="item-name">{{ $transaction->item->item_name }}</h2>
                    <p class="item-price">
                        <span class="yen">¥</span>
                        {{ $transaction->item->price }}
                    </p>
                </div>
            </div>
            <div class="chat-area">
                <div class="chat__content">
                    @foreach ($messages as $message)
                        @if ($message->sender_id === $user->id)
                            <div class="my-message">
                                <p class="chat__user-name">{{ $user->name }}</p>
                                <p>{{ $message->body }}</p>
                                <p>{{ $message->image ?? '' }}</p>
                            </div>
                        @else
                            <div class="partner-message">
                                <p class="chat__user-name">{{ $partner->name }}</p>
                                <p>{{ $message->body }}</p>
                                <p>{{ $message->image ?? '' }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
                {{-- メッセージ入力欄 --}}
                <form class="chat__form" action="{{ route('chat.store', ['transaction' => $transaction->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input class="chat__input" type="text" name="body" placeholder="取引メッセージを記入してください" value="{{ old('body') }}">
                    <label class="file__upload-btn">
                        画像を追加
                        <input class="file-input" name="image" type="file">
                    </label>
                    <button class="chat__submit" type="submit">
                        <img class="send-icon" src="{{ asset('materials/send-chat.png') }}" alt="送信">
                    </button>
                </form>
                @if($errors->hasAny(['body', 'image']))
                    <p class="error">
                        {{ $errors->first('body') ?: $errors->first('image') }}
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection