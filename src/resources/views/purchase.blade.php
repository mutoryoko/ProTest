@extends('layouts.default')

@section('title', '商品購入')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}" />
@endsection

@section('content')
    <div class="content">
        <form class="payment-form" action="{{ route('checkout') }}" method="POST">
        @csrf
            <div class="confirmation__wrapper">
                <div class="item-info">
                    <div class="item-image__wrapper">
                        <img class="item-image" src="{{ asset('storage/'.$item->item_image) }}" alt="商品画像" />
                    </div>
                    <div>
                        <h1 class="item-name">{{ $item->item_name }}</h1>
                        <p class="item-price"><span class="yen">¥</span>{{ number_format($item->price) }}</p>
                    </div>
                </div>
                <div class="payment-method">
                    <h2 class="small-ttl">支払い方法</h2>
                    <details class="custom-dropdown">
                        <summary class="dropdown__default">
                            <span>{{ $selectedPaymentName ?? '選択してください' }}</span>
                            <span class="arrow">▼</span>
                        </summary>
                        <div class="dropdown__options">
                            @foreach($paymentMethods as $key => $name)
                                {{-- クエリパラメータ付きのリンクを生成 --}}
                                <a class="payment-method__link" href="{{ request()->fullUrlWithQuery(['payment_method' => $key]) }}">{{ $name }}</a>
                            @endforeach
                        </div>
                    </details>
                    <input type="hidden" name="payment_method" value="{{ $selectedPaymentKey }}">

                    @error('payment_method')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="shipping-info">
                    <div class="shipping__head">
                        <h2 class="small-ttl">配送先</h2>
                        <a class="shipping__link" href="{{ route('address.edit', ['item_id' => $item->id]) }}">変更する</a>
                    </div>
                    <p class="postcode">〒{{ $profile->postcode ?? '' }}</p>
                    <input name="shipping_postcode" type="hidden" value="{{ $profile->postcode ?? '' }}">
                    <p class="address">{{ $profile->address ?? '' }}</p>
                    <input name="shipping_address" type="hidden" value="{{ $profile->address ?? '' }}">
                    <p class="building">{{ $profile->building ?? '' }}</p>
                    <input name="shipping_building" type="hidden" value="{{ $profile->building ?? '' }}">
                    @if ($errors->hasAny(['shipping_postcode', 'shipping_address']))
                        <p class="shipping__error">
                            {{ $errors->first('shipping_postcode') ?: $errors->first('shipping_address') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="payment__wrapper">
                <table class="payment-table">
                    <tr class="table-row">
                        <th class="table-th">商品代金</th>
                        <td class="table-td table__price"><span class="yen">¥</span>{{ number_format($item->price) }}</td>
                    </tr>
                    <tr class="table-row">
                        <th class="table-th">支払い方法</th>
                        <td class="table-td" id="selected-payment-method">{{ $selectedPaymentName ?? '選択されていません' }}</td>
                    </tr>
                </table>
                @if($isSold)
                    <button class="isSold" disabled>売り切れ</button>
                @else
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <button class="submit__btn" type="submit">購入する</button>
                @endif
            </div>
        </form>
    </div>
@endsection