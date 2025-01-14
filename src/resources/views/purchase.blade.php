@extends('layouts.app')

@section('script')
<script src="https://js.stripe.com/v3/"></script>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form id="form1" class="purchase-form" method="post" action="{{ route('storeOrder', ['item_id' => $item->id]) }}" >
    @csrf
    <div class="purchase-info">
        <div class="item">
            <img class="item-img" src="{{ asset('storage/image/' . $item->image) }}" alt="商品画像">
            <div class="item-info">
                <h2 class="item-name">{{ $item->name }}</h2>
                <p class="item-price">&yen;<span>{{ number_format($item->price) }}</span>
            </div>
        </div>
        <div class="content-area">
            <h3 class="content-heading">支払い方法</h3>
            <div class="content-pay">
                <div class="content-select">
                    <div class="selected-option" id="selectedOption">選択してください</div>
                    <ul class="options-list" id="optionsList" style="display: none;">
                        <li class="option-item" data-value="コンビニ払い">コンビニ払い</li>
                        <li class="option-item" data-value="カード払い">カード支払い</li>
                    </ul>
                </div>
                <input type="hidden" name="payment" id="pay" value="">
                <p class="purchase-form__error-message" id="pay-error"></p>
            </div>
        </div>
        <div class="content-area">
            <h3 class="content-heading">配送先</h3>
            <a class="content-link" href="{{ route('address', ['item_id'=>$item->id]) }}">変更する</a>
            <div class="content-detail">
                <span>〒</span><input type="text" name="zip" value="{{ old('zip', $profile->zip) }}" readonly>
                <textarea name="address" readonly>{{ old('address', $profile->address . ' ' . $profile->building) }}</textarea>
            </div>
        </div>
    </div>
</form>
<div class="confirm">
    <div class="confirm-contents__box">
        <div class="confirm-content">
            <p class="confirm-content__name">商品代金</p>
            <p class="confirm-content__detail">&yen;<span>{{ number_format($item->price) }}</span></p>
        </div>
        <div class="confirm-content">
            <p class="confirm-content__name">支払い方法</p>
            <p class="confirm-content__detail" id="selected-payment"></p>
        </div>
    </div>
    <form id="form2" action="{{ route('checkout', ['item_id' => $item->id]) }}" method="POST">
    @csrf
        <button class="purchase-form__button" type="submit" id="checkout-button">購入する</button>
    </form>
</div>

<script>
document.getElementById('checkout-button').addEventListener('click', function(event) {
    event.preventDefault();

    const selectedPayment = document.getElementById('selectedOption').textContent;
    if (selectedPayment === '選択してください') {
        document.getElementById('pay-error').innerText = '支払い方法を選択してください';
        return;
    }

    fetch(document.getElementById('form1').action, {
        method: 'POST',
        body: new FormData(document.getElementById('form1'))
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorMessage = data.errors[field][0];
                        document.getElementById(`${field}-error`).textContent = errorMessage;
                    });
                } else {
                    console.error('予期せぬエラーが発生しました:', data);
                    alert('処理中にエラーが発生しました。しばらくしてから再度お試しください。');
                }
            });
        }
        document.getElementById('form2').submit();
    })
    .catch(error => {
        console.error('エラー:', error);
        alert('処理中にエラーが発生しました。しばらくしてから再度お試しください。');
    });
});
</script>
@endsection