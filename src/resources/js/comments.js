document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('comment-form');
    const commentCountElement = document.querySelector('.comment-count');
    const textarea = document.getElementById('comment-textarea');
    const itemId = form.getAttribute('action').split('/').pop();



        try {
            const formData = new FormData(form);

            const response = await fetch(`/items/${itemId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData,
            });

            if (!response.ok) throw new Error('コメント送信に失敗しました');

            const data = await response.json();

            commentCountElement.textContent = data.commentsCount;

            textarea.value = '';
        } catch (error) {
            console.error('コメント送信でエラーが発生しました:', error);
        }

});