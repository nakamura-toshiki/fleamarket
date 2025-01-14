@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-form">
    <h2 class="address-form__heading">住所の変更</h2>
    <div class="address-form__inner">
        <form class="address-form__form" action="{{ route('newAddress', ['item_id'=>$item->id]) }}" method="post">
        @csrf
            <div class="address-form__group">
                <label class="address-form__label" for="zip">郵便番号</label>
                <input class="address-form__input" type="text" name="zip" id="zip" value="{{ old('zip', $address->zip) }}">
                <p class="address-form__error-message">
                    @error('zip')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="address-form__group">
                <label class="address-form__label" for="address">住所</label>
                <input class="address-form__input" type="text" name="address" id="address" value="{{ old('address', $address->address) }}">
                <p class="address-form__error-message">
                    @error('address')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="address-form__group">
                <label class="address-form__label" for="building">建物名</label>
                <input class="address-form__input" type="text" name="building" id="building" value="{{ old('building', $address->building) }}">
                <p class="address-form__error-message">
                    @error('building')
                    {{ $message }}
                    @enderror
                </p>
            <input class="address-form__btn" type="submit" value="更新する">
        </form>
    </div>
</div>
@endsection