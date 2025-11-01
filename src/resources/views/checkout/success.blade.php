@extends('layouts.default')

@section('title', '購入完了')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
@endsection

@section('content')
    <div class="content">
        <div class="thanks">Thank you!</div>
        <h1 class="head">ご購入いただき、ありがとうございます！</h1>
        <p>商品のお支払いが完了しました。</p>
        <a class="back__link" href="{{ route('mypage.index', ['page' => 'buy'])}}">マイページへ戻る</a>
    </div>
@endsection