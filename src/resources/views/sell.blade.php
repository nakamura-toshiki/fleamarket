@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<h1 class="content-heading">商品の出品</h1>
<form class="sell-form" action="{{ route('store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="content-item">
        <h3 class="content-item__label">商品画像</h3>
        <div class="content-item__img">
            <label class="content-item__img-label" for="image">画像を選択する</label>
            <input class="content-item__img-input" type="file" name="image" id="image" style="display: none;">
            <p class="sell-form__error-message">
                @error('image')
                    {{ $message }}
                @enderror
            </p>
        </div>
    </div>
    <div class="content-group">
        <h2 class="content-group__heading">商品の詳細</h2>
        <div class="content-item">
            <h3 class="content-item__label">カテゴリー</h3>
            <div class="content-item__categories">
                @foreach($categories as $index => $category)
                <div class="content-item__category-wrapper">
                    <input type="checkbox" name="category_id[]" id="category_{{ $index }}" value="{{ $category->id }}" {{ in_array($category->id, old('category_id', [])) ? 'checked' : '' }}>
                    <label class="content-item__category-label" for="category_{{ $index }}">
                        {{ $category->content }}
                    </label>
                </div>
                @endforeach
            </div>
            <p class="sell-form__error-message">
                @error('category_id')
                    {{ $message }}
                @enderror
            </p>
        </div>
        <div class="content-item">
            <h3 class="content-item__label">商品の状態</h3>
            <div class="content-item__select">
                <div class="content-item__select-box" >
                    <div class="selected-option" id="selectedOptionSell">選択してください</div>
                    <ul class="options-list" id="optionsListSell" style="display: none;">
                        <li class="option-item" data-value="良好">良好</li>
                        <li class="option-item" data-value="目立った傷や汚れなし">目立った傷や汚れなし</li>
                        <li class="option-item" data-value="やや傷や汚れあり">やや傷や汚れあり</li>
                        <li class="option-item" data-value="状態が悪い">状態が悪い</li>
                    </ul>
                </div>
                <input type="hidden" name="condition" id="condition" value="{{ old('condition') }}">
                <p class="sell-form__error-message">
                    @error('condition')
                        {{ $message }}
                    @enderror
                </p>
            </div>
        </div>
    </div>
    <div class="content-group">
        <h2 class="content-group__heading">商品名と説明</h2>
        <div class="content-item">
            <h3 class="content-item__label">商品名</h3>
            <div class="content-item__txt">
                <input class="content-item__txt-input" type="text" name="name" value="{{ old('name') }}">
                <p class="sell-form__error-message">
                    @error('name')
                        {{ $message }}
                    @enderror
                </p>
            </div>
        </div>
        <div class="content-item">
            <h3 class="content-item__label">ブランド名</h3>
            <div class="content-item__txt">
                <input class="content-item__txt-input" type="text" name="brand" value="{{ old('brand') }}">
            </div>
        </div>
        <div class="content-item">
            <h3 class="content-item__label">商品の説明</h3>
            <textarea class="content-item__textarea" name="description">{{ old('description') }}</textarea>
            <p class="sell-form__error-message">
                    @error('description')
                        {{ $message }}
                    @enderror
                </p>
        </div>
        <div class="content-item">
            <h3 class="content-item__label">販売価格</h3>
            <div class="content-item__price">
                <input class="content-item__price-input" type="text" name="price" value="{{ old('price') }}">
            </div>
            <p class="sell-form__error-message">
                    @error('price')
                        {{ $message }}
                    @enderror
            </p>
        </div>
    </div>
    <input class="form-button" type="submit" value="出品する">
</form>
@endsection