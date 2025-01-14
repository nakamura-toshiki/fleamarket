document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', async (e) => {
            const itemId = button.getAttribute('data-item-id');
            const image = button.querySelector('.like-image');

            if (button.classList.contains('disabled')) return;

            try {
                const response = await fetch(`/items/${itemId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                });

                if (response.ok) {
                    const data = await response.json();
                    const likeCountElement = document.getElementById(`like-count-${itemId}`);

                    if (data.liked) {
                        image.src = '/storage/star-clicked.png';
                        button.classList.add('liked');
                    } else {
                        image.src = '/storage/star.png';
                        button.classList.remove('liked');
                    }

                    likeCountElement.textContent = data.likesCount;
                }
            } catch (error) {
                console.error('いいね処理でエラーが発生しました:', error);
            }
        });
    });
});