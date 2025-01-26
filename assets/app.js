/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import './js/post-actions.js';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.querySelector('.hidden-file-input');
    const previewContainer = document.getElementById('previewContainer');
    const previewImage = document.getElementById('preview-image');
    const removeButton = document.querySelector('.btn-remove-image');

    if (uploadArea) {
        // Make the entire upload area clickable
        uploadArea.addEventListener('click', function() {
            fileInput.click();
        });

        // Drag and drop handlers
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            uploadArea.classList.add('dragover');
        }

        function unhighlight(e) {
            uploadArea.classList.remove('dragover');
        }

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }

        // File input change handler
        fileInput.addEventListener('change', function(e) {
            e.stopPropagation(); // Prevent click event from bubbling to uploadArea
            handleFiles(this.files);
        });

        // Remove image handler
        if (removeButton) {
            removeButton.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent click event from bubbling to uploadArea
                fileInput.value = '';
                previewContainer.classList.add('d-none');
                uploadArea.querySelector('.upload-area-content').classList.remove('d-none');
            });
        }

        function handleFiles(files) {
            if (files.length > 0) {
                const file = files[0];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewContainer.classList.remove('d-none');
                        uploadArea.querySelector('.upload-area-content').classList.add('d-none');
                    }
                    reader.readAsDataURL(file);
                }
            }
        }
    }
});
