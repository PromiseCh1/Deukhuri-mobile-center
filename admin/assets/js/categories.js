/**
 * categories.js – Category Module JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {

    // ============================================
    // 1. DELETE CONFIRMATION MODAL
    // ============================================
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        const closeBtn = document.getElementById('deleteModalClose');
        const cancelBtn = document.getElementById('deleteModalCancel');
        const confirmBtn = document.getElementById('deleteModalConfirm');
        const messageEl = document.getElementById('deleteModalMessage');
        const warningEl = document.getElementById('deleteModalWarning');

        function showDeleteModal(url, name, hasProducts) {
            deleteUrl = url;
            messageEl.textContent = `Are you sure you want to delete the category "${name}"?`;
            if (hasProducts) {
                warningEl.style.display = 'block';
                confirmBtn.style.display = 'none';
            } else {
                warningEl.style.display = 'none';
                confirmBtn.style.display = 'inline-block';
                confirmBtn.href = url;
            }
            deleteModal.style.display = 'flex';
        }

        function closeModal() {
            deleteModal.style.display = 'none';
        }

        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) closeModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && deleteModal.style.display === 'flex') {
                closeModal();
            }
        });

        // Attach to delete buttons
        document.querySelectorAll('[data-modal="delete"]').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.dataset.url;
                const name = this.dataset.name || 'this category';
                const hasProducts = this.dataset.hasProducts === 'true';
                showDeleteModal(url, name, hasProducts);
            });
        });
    }

    // ============================================
    // 2. CLEAR SEARCH
    // ============================================
    const clearSearchBtn = document.getElementById('clearSearch');
    const searchForm = document.getElementById('searchForm');
    if (clearSearchBtn && searchForm) {
        clearSearchBtn.addEventListener('click', function() {
            const input = searchForm.querySelector('input[name="search"]');
            if (input) {
                input.value = '';
                searchForm.submit();
            }
        });
    }

    // ============================================
    // 3. SLUG GENERATION
    // ============================================
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    if (nameInput && slugInput) {
        function generateSlug(text) {
            return text.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        let slugManuallyChanged = false;
        slugInput.addEventListener('input', function() {
            slugManuallyChanged = true;
        });

        nameInput.addEventListener('input', function() {
            if (!slugManuallyChanged) {
                slugInput.value = generateSlug(this.value);
            }
        });

        if (!slugInput.value && nameInput.value) {
            slugInput.value = generateSlug(nameInput.value);
        }
    }

    // ============================================
    // 4. ALERT DISMISS & AUTO-HIDE
    // ============================================
    document.querySelectorAll('.alert .alert-close').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const alert = this.closest('.alert');
            if (alert) {
                alert.style.transition = 'opacity 0.3s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    if (alert.parentNode) alert.remove();
                }, 300);
            }
        });
    });

    // Auto-hide alerts after 5 seconds
    document.querySelectorAll('.alert:not(.alert-permanent)').forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                if (alert.parentNode) alert.remove();
            }, 500);
        }, 5000);
    });

    // ============================================
    // 5. IMAGE PREVIEW (for form)
    // ============================================
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = document.querySelector('.image-preview img');
                    if (!preview) {
                        const wrapper = document.querySelector('.image-upload-wrapper');
                        const div = document.createElement('div');
                        div.className = 'image-preview';
                        div.innerHTML = `<img src="${e.target.result}" alt="Preview"><button type="button" class="btn-remove-image" id="removeImageBtn">✕</button>`;
                        wrapper.prepend(div);
                    } else {
                        preview.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Remove image button (event delegation)
    document.addEventListener('click', function(e) {
        if (e.target.id === 'removeImageBtn' || e.target.closest('#removeImageBtn')) {
            const btn = e.target.closest('#removeImageBtn');
            const preview = btn.closest('.image-preview');
            if (preview) {
                preview.remove();
                const fileInput = document.getElementById('image');
                if (fileInput) fileInput.value = '';
                const existing = document.querySelector('input[name="existing_image"]');
                if (existing) {
                    existing.value = ''; // signal removal
                }
            }
        }
    });

    console.log('Category JS loaded.');
});