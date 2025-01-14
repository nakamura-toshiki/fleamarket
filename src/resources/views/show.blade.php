@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')
<div class="left-contents">
    <img class="item-img" src="{{ asset('storage/image/' . $item->image) }}" alt="">
</div>
<div class="right-contents">
    <div class="item-info__group">
        <h1 class="item-name">{{ $item->name }}</h1>
        <p class="item-brand">{{ $item->brand }}</p>
        <p class="item-price">
            &yen;<span>{{ number_format($item->price) }}</span>(税込)
        </p>
        <div class="item-reaction">
            <div class="like-section">
                @auth
                    <button class="like-button {{ $isLiked ? 'liked' : '' }}" data-item-id="{{ $item->id }}">
                        @if ($isLiked)
                            <img src="{{ asset('storage/star-clicked.png') }}" alt="いいね済み" class="like-image__liked">
                        @else
                            <img src="{{ asset('storage/star.png') }}" alt="いいね未済み" class="like-image">
                        @endif
                    </button>
                    <span class="count" id="like-count-{{ $item->id }}">
                        {{ $item->likesCount() }}
                    </span>
                @else
                    <button class="like-button disabled" disabled>
                        <img src="{{ asset('storage/star.png') }}" alt="いいね" class="like-image">
                    </button>
                    <span class="count">{{ $item->likesCount() }}</span>
                @endauth
            </div>
            <div class="comment-section">
                <div class="comment-img">
                    <img src="{{ asset('storage/comment.png') }}" alt="ふきだし">
                </div>
                <span class="count" id="comment-count-{{ $item->id }}">
                    @if(!$comment)
                        0
                    @else
                        {{ $commentCount }}
                    @endif
                </span>
            </div>
        </div>
    </div>
    <div class="buy-link__box">
        <a class="buy-link" href="{{ $profile ? route('order', ['item_id'=>$item->id]) : route('edit') }}">購入手続きへ</a>
    </div>
    <div class="item-info__group">
        <h2 class="item-info__heading">商品説明</h2>
        <p class="item-description__content">{{ $item->description }}</p>
    </div>
    <div class="item-info__group">
        <h2 class="item-info__heading">商品の情報</h2>
        <div class="item-category__content">
            <p class="content-label">カテゴリー</p>
            <div class="item-category__wrap">
                @if($item->categories->count() > 0)
                    @foreach($item->categories as $category)
                        <p class="item-category">{{ $category->content }}</p>
                    @endforeach
                @else
                    <p></p>
                @endif
            </div>
        </div>
        <div class="item-condition__content">
            <p class="content-label">商品の状態</p>
            <p class="item-condition">{{ $item->condition }}</p>
        </div>
    </div>
    <div class="item-info__group">
        <h2 class="item-comment__heading">コメント
            @if(!$comment)
                (0)
            @else
                ({{ $commentCount }})
            @endif
        </h2>
        <div class="item-comment__user">
            @if($item->comments->isNotEmpty())
                @php
                    $firstComment = $item->comments->first();
                @endphp
                @if(optional($firstComment->user->profile)->image)
                    <div class="profile-img">
                        <img src="{{ asset('storage/' . optional($firstComment->user->profile)->image) }}">
                    </div>
                @else
                    <div class="profile-img">
                        <div class="circle"></div>
                    </div>
                @endif
                <h3 class="profile-name">{{ optional($firstComment->user)->name }}</h3>
            @else
                <div class="profile-img">
                    <div class="circle"></div>
                </div>
                <h3 class="profile-name">admin</h3>
            @endif
        </div>
        <div class="item-comment__box">
            @if($comment)
                <p class="item-comment__box-txt">{{ $comment->comment }}</p>
            @else
                <p class="item-comment__box-txt">こちらにコメントが入ります。</p>
            @endif
        </div>
        <form class="item-comment__form" action="{{ route('comment', ['item_id'=>$item->id]) }}" method="post">
            @csrf
            <h3 class="item-comment__textarea-label">
                商品へのコメント
            </h3>
            <textarea class="item-comment__textarea" name="comment"></textarea>
            <p class="comment-form__error-message">
                @error('comment')
                    {{ $message }}
                @enderror
            </p>
            <input class="item-comment__button" type="submit" value="コメントを送信する">
        </form>
    </div>
</div>
@endsection