@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-contents">
    @if($user->profile->image)
        <img class="profile-img" src="{{ asset('storage/' . $user->profile->image) }}">
    @else
        <div class="circle"></div>
    @endif
    <h2 class="profile-name">{{ auth()->user()->name }}</h2>
    <a class="profile-edit__link" href="{{ route('edit') }}">
        プロフィールを編集
    </a>
</div>
<div class="tab-menu">
    <a class="tab {{ $tab === 'sell' ? 'selected' : '' }}" href="{{ route('mypage', ['tab' => 'sell']) }}">出品した商品</a>
    <a class="tab {{ $tab === 'buy' ? 'selected' : '' }}" href="{{ route('mypage', ['tab' => 'buy']) }}">購入した商品</a>
</div>
<div class="content-items">
    @if($tab === 'buy')
        @foreach($user->buys as $buy)
        <div class="content-item">
            <a class="content-item__link" href="{{ route('show', ['item_id'=>$buy->item->id]) }}">
                <div class="content-item__img">
                    <img src="{{ asset('storage/image/' . $buy->item->image) }}" alt="商品画像">
                </div>
                <p class="content-item__name">{{ $buy->item->name }}</p>
            </a>
        </div>
        @endforeach
    @else
        @foreach($user->items as $item)
        <div class="content-item">
            <a class="content-item__link" href="{{ route('show', ['item_id'=>$item->id]) }}">
                <div class="content-item__img">
                    <img src="{{ asset('storage/image/' . $item->image) }}" alt="商品画像">
                </div>
                <p class="content-item__name">{{ $item->name }}</p>
            </a>
        </div>
        @endforeach
    @endif
</div>
@endsection