@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endsection

@section('content')
<div class="edit-form">
    <h1 class="edit-form__heading">プロフィール設定</h1>
    <form class="edit-form__form" action="{{ route('update') }}" method="post" enctype="multipart/form-data">
    @csrf
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        <div class="form-img">
            @if($profile->image)
                <img src="{{ asset('storage/' . $profile->image) }}">
            @else
                <div class="circle"></div>
            @endif
            <label class="form-img__label" for="image">画像を選択する</label>
            <input type="file" name="image" id="image" style="display: none;">
        </div>
        <div class="form-group">
            <label class="form-label" for="name">ユーザー名</label>
            <input class="form-input" type="text" name="name" id="name" value="{{ auth()->user()->name }}">
            <p class="edit-form__error-message">
                @error('name')
                    {{ $message }}
                @enderror
            </p>
        </div>
        <div class="form-group">
            <label class="form-label" for="zip">郵便番号</label>
            <input class="form-input" type="text" name="zip" id="zip" value="{{ old('zip', $profile->zip) }}">
            <p class="edit-form__error-message">
                @error('zip')
                    {{ $message }}
                @enderror
            </p>
        </div>
        <div class="form-group">
            <label class="form-label" for="address">住所</label>
            <input class="form-input" type="text" name="address" id="address" value="{{ old('address', $profile->address) }}">
            <p class="edit-form__error-message">
                @error('address')
                    {{ $message }}
                @enderror
            </p>
        </div>
        <div class="form-group">
            <label class="form-label" for="building">建物名</label>
            <input class="form-input" type="text" name="building" id="building" value="{{ old('building', $profile->building) }}">
            <p class="edit-form__error-message">
                @error('building')
                    {{ $message }}
                @enderror
            </p>
        </div>
        <input class="form-btn" type="submit" value="更新する">
    </form>
</div>
@endsection