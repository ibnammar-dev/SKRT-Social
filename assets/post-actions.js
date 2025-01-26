// Post editing functionality
function editPost(postId) {
    fetch(`/post/${postId}/edit-form`)
        .then(response => response.text())
        .then(html => {
            document.querySelector(`.post-content-${postId}`).innerHTML = html;
        });
}

function cancelEdit(postId) {
    fetch(`/post/${postId}/content`)
        .then(response => response.text())
        .then(html => {
            document.querySelector(`.post-content-${postId}`).innerHTML = html;
        });
}

function saveEdit(postId) {
    const content = document.querySelector(`.post-content-${postId} textarea`).value;
    
    fetch(`/post/${postId}/edit`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ text: content })
    })
    .then(response => response.text())
    .then(html => {
        document.querySelector(`.post-content-${postId}`).innerHTML = html;
    });
}

// Post deletion functionality
let postToDelete = null;

function confirmDelete(postId) {
    postToDelete = postId;
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    modal.show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!postToDelete) return;
    
    const postElement = document.getElementById(`post-${postToDelete}`);
    
    fetch(`/post/${postToDelete}/delete`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (response.ok) {
            postElement.style.opacity = 0;
            setTimeout(() => {
                postElement.style.height = postElement.offsetHeight + 'px';
                postElement.style.marginBottom = '0';
                postElement.style.padding = '0';
                setTimeout(() => {
                    postElement.style.height = '0';
                    setTimeout(() => postElement.remove(), 300);
                }, 50);
            }, 300);
        }
    });
    
    bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
    postToDelete = null;
}); 