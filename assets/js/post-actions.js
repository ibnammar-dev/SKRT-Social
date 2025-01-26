// Post creation functionality
window.createPost = function() {
    const content = document.querySelector('.create-post-form textarea').value;
    const imageInput = document.querySelector('#imageInput');
    
    const formData = new FormData();
    formData.append('text', content);
    if (imageInput.files[0]) {
        formData.append('image', imageInput.files[0]);
    }
    
    fetch('/post/create', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.text())
    .then(html => {
        // Insert new post at the top of the posts list
        const postsContainer = document.querySelector('.posts-container');
        postsContainer.insertAdjacentHTML('afterbegin', html);
        
        // Clear form
        document.querySelector('.create-post-form textarea').value = '';
        document.querySelector('#imagePreview').classList.add('d-none');
        imageInput.value = '';
    });
}

// Post editing functionality
window.editPost = function(postId) {
    fetch(`/post/${postId}/edit-form`)
        .then(response => response.text())
        .then(html => {
            const postContent = document.querySelector(`.post-content-${postId}`);
            postContent.innerHTML = html;
            // Focus on textarea
            postContent.querySelector('textarea').focus();
        });
}

window.cancelEdit = function(postId) {
    fetch(`/post/${postId}/content`)
        .then(response => response.text())
        .then(html => {
            document.querySelector(`.post-content-${postId}`).innerHTML = html;
        });
}

window.saveEdit = function(postId) {
    const content = document.querySelector(`.post-content-${postId} textarea`).value;
    const imageInput = document.querySelector(`#editImageInput-${postId}`);
    
    const formData = new FormData();
    formData.append('text', content);
    if (imageInput && imageInput.files[0]) {
        formData.append('image', imageInput.files[0]);
    }
    
    fetch(`/post/${postId}/edit`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.text())
    .then(html => {
        document.querySelector(`.post-content-${postId}`).innerHTML = html;
    });
}

// Image handling
window.triggerImageUpload = function() {
    document.querySelector('#imageInput').click();
}

window.handleImageUpload = function(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.querySelector('#imagePreview');
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('d-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

window.removeNewImage = function() {
    document.querySelector('#imageInput').value = '';
    document.querySelector('#imagePreview').classList.add('d-none');
}

// Post deletion functionality
let postToDelete = null;

window.confirmDelete = function(postId) {
    postToDelete = postId;
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
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
});

// Add this to your existing code
window.removeImage = function(postId) {
    fetch(`/post/${postId}/remove-image`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.text())
    .then(html => {
        document.querySelector(`.post-content-${postId}`).innerHTML = html;
    });
}

// Add these functions to your existing code
window.triggerEditImageUpload = function(postId) {
    document.querySelector(`#editImageInput-${postId}`).click();
}

window.handleEditImageUpload = function(input, postId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.querySelector(`#editImagePreview-${postId}`);
            const uploadArea = document.querySelector(`#uploadArea-${postId}`);
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('d-none');
            uploadArea.classList.add('d-none');  // Hide upload area
        }
        reader.readAsDataURL(input.files[0]);
    }
}

window.removeEditImage = function(postId) {
    const imageInput = document.querySelector(`#editImageInput-${postId}`);
    const preview = document.querySelector(`#editImagePreview-${postId}`);
    const uploadArea = document.querySelector(`#uploadArea-${postId}`);
    imageInput.value = '';
    preview.classList.add('d-none');
    uploadArea.classList.remove('d-none');  // Show upload area
} 