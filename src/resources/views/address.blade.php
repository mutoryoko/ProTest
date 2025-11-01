@extends('layouts.default')

@section('title', '送付先変更')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/address.css')}}" />
@endsection

@section('content')
    <div class="content">
        <h1 class="title">住所の変更</h1>
        <form class="user-form" action="{{ route('address.update', ['item_id' => $item->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="user-form__item">
                <label for="postcode" class="user-form__label"><div>郵便番号</div></label>
                <input id="postcode" class="user-form__input" type="text" name="postcode" value="{{ old('postcode', $profile->postcode ?? '') }}">
                @error('postcode')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <div class="user-form__item">
                <label for="address" class="user-form__label"><div>住所</div></label>
                <input id="address" class="user-form__input" type="text" name="address" value="{{ old('address', $profile->address ?? '') }}">
                @error('address')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <div class="user-form__item">
                <label for="building" class="user-form__label"><div>建物名</div></label>
                <input id="building" class="user-form__input" type="text" name="building" value="{{ old('building', $profile->building ?? '') }}">
            </div>
            <button class="submit__btn update__btn" type="submit">更新する</button>
        </form>
    </div>
@endsection