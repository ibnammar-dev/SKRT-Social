// Toast notification function
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = 'toast-notification slide-in';
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
            ${message}
        </div>
    `;
    document.body.appendChild(toast);

    // Trigger reflow for animation
    toast.offsetHeight;
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Post creation functionality
document.addEventListener('DOMContentLoaded', function() {
    const createPostForm = document.querySelector('.js-create-post-form');
    if (createPostForm) {
        createPostForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            
            // Disable submit button and show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Posting...';
            
            fetch(this.dataset.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                // Create a temporary container for the new post
                const tempContainer = document.createElement('div');
                tempContainer.innerHTML = html;
                const newPost = tempContainer.firstElementChild;
                
                // Add initial styles for animation
                newPost.style.opacity = '0';
                newPost.style.transform = 'translateY(-20px)';
                newPost.style.transition = 'all 0.5s ease-out';
                
                // Insert the new post at the top
                const postsContainer = document.querySelector('.posts-container');
                
                // Remove the "no posts" message if it exists
                const noPostsMessage = postsContainer.querySelector('.text-muted');
                if (noPostsMessage) {
                    noPostsMessage.remove();
                }
                
                // Add the new post
                postsContainer.insertBefore(newPost, postsContainer.firstChild);
                
                // Trigger reflow and add animation
                newPost.offsetHeight;
                newPost.style.opacity = '1';
                newPost.style.transform = 'translateY(0)';
                
                // Clear form
                createPostForm.reset();
                const previewContainer = document.querySelector('#previewContainer');
                if (previewContainer) {
                    previewContainer.classList.add('d-none');
                }
                
                // Show success toast
                showToast('Post created successfully!');
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while creating the post. Please try again.', 'error');
            })
            .finally(() => {
                // Re-enable submit button and restore original text
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Post';
            });
        });
    }
});

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