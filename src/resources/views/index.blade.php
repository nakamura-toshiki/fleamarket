@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
@if(session('message'))
    <div class="checkout-alert">
        {{ session('message') }}
    </div>
@endif
<div class="tab-menu">
    <a class="tab {{ $tab === 'recommend' ? 'selected' : '' }}" href="{{ route('index', array_merge(request()->query(), ['tab' => 'recommend'])) }}">おすすめ</a>
    <a class="tab {{ $tab === 'mylist' ? 'selected' : '' }}" href="{{ route('index', array_merge(request()->query(), ['tab' => 'mylist'])) }}">マイリスト</a>
</div>
<div class="content-items">
    @foreach($items as $item)
    <div class="content-item">
        @if($item->is_sold)
            <div class="content-item__card">
                <div class="content-item__img">
                    <img src="{{ asset('storage/image/' . $item->image) }}" alt="商品画像">
                    <div class="content-item__sold">
                        <p class="content-item__sold-txt">Sold</p>
                    </div>
                </div>
                <p class="content-item__name">{{ $item->name }}</p>
            </div>
        @else
            <a class="content-item__link" href="{{ route('show', ['item_id'=>$item->id]) }}">
                <div class="content-item__img">
                    <img src="{{ asset('storage/image/' . $item->image) }}" alt="商品画像">
                </div>
                <p class="content-item__name">{{ $item->name }}</p>
            </a>
        @endif
    </div>
    @endforeach
</div>
@endsection