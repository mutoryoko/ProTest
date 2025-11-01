@extends('layouts.default')

@section('title', 'プロフィール設定')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/edit.css')}}" />
@endsection

@section('content')
    <div class="content">
        <h2 class="title">プロフィール設定</h2>
        <form class="user-form" action="{{ route('mypage.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
            <div class="user-form__item">
                @livewire('profile-image-preview', ['existingImagePath' => $profile->user_image ?? ''])
                @error('user_image')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <div class="user-form__item">
                <label for="user_name" class="user-form__label"><div>ユーザー名</div></label>
                <input id="user_name" class="user-form__input" type="text" name="user_name" value="{{ old('user_name', $user->name ?? '') }}">
                @error('user_name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
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