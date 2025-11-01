@extends('layouts.default')

@section('title', '商品詳細')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}" />
@endsection

@section('content')
    <div class="content">
        <div class="item-image__wrapper">
            <img class="item-image" src="{{ asset('storage/'.$item->item_image) }}" alt="商品画像" />
        </div>
        <div class="item-info__wrapper">
            <h1 class="item-name">{{ $item->item_name }}</h1>
            <p class="item-brand">{{ $item->brand ?? '' }}</p>
            <p class="item-price"><span class="yen">¥</span>{{ number_format($item->price) }}<span class="tax">（税込）</span></p>

            <div class="likes-comments__icons">
                {{-- いいね機能 --}}
                <div class="like-icon">
                    @if(Auth::check() && Auth::user()->likeItems($item->id))
                        <form class="like-form" action="{{ route('unlike', ['item_id' => $item->id] )}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="like-btn">
                                <img class="like-icon__img" src="{{ asset('storage/materials/like-on.png') }}" alt="いいね済みのアイコン">
                            </button>
                        </form>
                    @else
                        <form class="like-form" action="{{ route('like', ['item_id' => $item->id] )}}" method="POST">
                            @csrf
                            <button type="submit" class="like-btn">
                                <img class="like-icon__img" src="{{ asset('storage/materials/like-off.png') }}" alt="いいね前のアイコン">
                            </button>
                        </form>
                    @endif
                    <p class="count">{{ $item->likes()->count() }}</p>
                </div>

                <div class="comment-icon">
                    <img class="comment-icon__img" src="{{ asset('storage/materials/comment-icon.png') }}" alt="コメントのアイコン">
                    <p class="count">{{ $comments->count() }}</p>
                </div>
            </div>

            <div>
                @if($isSold)
                    <button class="isSold" disabled>売り切れ</button>
                @else
                    <a class="purchase__btn" href="{{ route('purchase', ['item_id' => $item->id]) }}">購入手続きへ</a>
                @endif
            </div>

            <div>
                <h2 class="ttl">商品説明</h2>
                <p class="description-content">{{ $item->description }}</p>
            </div>
            <div>
                <h2 class="ttl">商品の情報</h2>
                <div class="category">
                    <h3 class="small-ttl">カテゴリー</h3>
                    @foreach ($categories as $category)
                        <p class="category__name">{{ $category->name }}</p>
                    @endforeach
                </div>
                <div class="condition">
                    <h3 class="small-ttl">商品の状態</h3>
                    {{-- app/Helpers/helper.phpにテキストあり --}}
                    <p class="condition__text">{{ $item->condition_text }}</p>
                </div>
            </div>

            <div class="item-comments">
                <h2 class="comments-counter">コメント（{{ $comments->count() }}）</h2>
                @forelse($comments as $comment)
                    <div class="user-info">
                        @if($comment->user->profile && $comment->user->profile->user_image)
                            <div class="user-image__wrapper">
                                <img src="{{ asset('storage/'.$comment->user->profile->user_image) }}" alt="ユーザーアイコン" class="user-image" />
                            </div>
                        @else
                            <div class="user-image__wrapper">
                                <img src="{{ asset('storage/profile-images/no-image.png')}}" alt="no-image" class="user-image">
                            </div>
                        @endif
                        <p class="user-name">{{ $comment->user->name}}</p>
                    </div>
                    <p class="comment__content">{{ $comment->content }}</p>
                @empty
                    <p class="no-comment">コメントはありません</p>
                @endforelse
            </div>

            <form class="comment-form" action="{{ route('comment', ['item_id' => $item->id]) }}" method="POST">
                @csrf
                <label for="comment"><div class="form-label">商品へのコメント</div></label>
                <textarea class="comment__text" name="content" id="comment" rows="8">{{ old('content') }}</textarea>
                @error('content')
                    <p class="error">{{ $message }}</p>
                @enderror
                <button class="comment__btn" type="submit">コメントを送信する</button>
            </form>

        </div>
    </div>
@endsection