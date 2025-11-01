@extends('layouts.default')

@section('title', 'プロフィール')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="profile__wrapper">
        <div class="user-info">
            <div class="user-image__wrapper">
                @if(!empty($profile->user_image))
                    <img class="user-img" src="{{ asset('storage/'.$profile->user_image) }}" alt="プロフィール画像">
                @else
                    <img class="user-img" src="{{ asset('storage/profile-images/no-image.png') }}" alt="no-image">
                @endif
            </div>
            <h1 class="user-name">{{ $user->name }}</h1>
        </div>
        <div>
            <a class="edit__btn" href="{{ route('mypage.profile.edit') }}">プロフィールを編集</a>
        </div>
    </div>

    <livewire:transaction-tabs :page="request('page')" />
</div>
@endsection