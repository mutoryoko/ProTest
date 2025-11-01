@extends('layouts.default')

@section('title', '購入キャンセル')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
@endsection

@section('content')
    <div class="content">
        <h1 class="head">購入がキャンセルされました⋯</h1>
        <p>お支払いは完了していません。再度ご購入手続きをお願いします。</p>
        <a class="back__link" href="/">トップページへ戻る</a>
    </div>
@endsection