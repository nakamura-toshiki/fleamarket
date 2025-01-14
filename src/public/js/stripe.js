document.addEventListener("DOMContentLoaded", function () {
    const publicKey = document.getElementById('checkout-button').dataset.publicKey;
    const stripe = Stripe(publicKey);

    document.getElementById('checkout-button').addEventListener('click', function (event) {
        event.preventDefault();

        const form = document.getElementById('purchase-form');
        const formData = new FormData(form);

        fetch(form.dataset.storeOrderUrl, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData,
        })
            .then(response => response.json())
            .then(() => {
                return fetch(form.dataset.createSessionUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        item_id: form.dataset.itemId,
                    }),
                });
            })
            .then(response => response.json())
            .then(data => {
                if (data.id) {
                    stripe.redirectToCheckout({ sessionId: data.id });
                } else {
                    alert("Stripeセッション作成中にエラーが発生しました。");
                }
            })
            .catch(error => console.error("エラー:", error));
    });
});