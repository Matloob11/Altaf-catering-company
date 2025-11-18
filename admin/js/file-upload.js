/**
 * Common File Upload Handler for Admin Panel
 * Handles image uploads with preview and validation
 */

function handleFileUpload(fileInput, pathInput, previewDiv, uploadType) {
    const file = fileInput.files[0];
    if (!file) return;

    // Validate file type
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
        alert('Invalid file type. Only JPG, PNG, GIF, and WEBP images are allowed.');
        fileInput.value = '';
        return;
    }

    // Validate file size (5MB max)
    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
        alert('File is too large. Maximum size is 5MB.');
        fileInput.value = '';
        return;
    }

    // Show preview
    const reader = new FileReader();
    reader.onload = function (e) {
        previewDiv.innerHTML = `
            <div class="position-relative d-inline-block">
                <img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                <div class="mt-2">
                    <small class="text-muted">${file.name} (${(file.size / 1024).toFixed(2)} KB)</small>
                </div>
            </div>
        `;
    };
    reader.readAsDataURL(file);

    // Upload file
    const formData = new FormData();
    formData.append('file', file);
    formData.append('type', uploadType);

    // Show loading
    const loadingHtml = previewDiv.innerHTML;
    previewDiv.innerHTML += '<div class="spinner-border spinner-border-sm ms-2" role="status"></div>';

    fetch('upload.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                pathInput.value = data.path;
                previewDiv.innerHTML = `
                <div class="position-relative d-inline-block">
                    <img src="../${data.path}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                    <span class="badge bg-success position-absolute top-0 end-0 m-2">
                        <i class="fas fa-check"></i> Uploaded
                    </span>
                    <div class="mt-2">
                        <small class="text-success">${file.name} uploaded successfully!</small>
                    </div>
                </div>
            `;
            } else {
                alert('Upload failed: ' + data.message);
                previewDiv.innerHTML = loadingHtml;
                fileInput.value = '';
            }
        })
        .catch(error => {
            alert('Upload error: ' + error);
            previewDiv.innerHTML = loadingHtml;
            fileInput.value = '';
        });
}

/**
 * Initialize file upload for a form
 * @param {string} fileInputId - ID of file input element
 * @param {string} pathInputId - ID of hidden path input element
 * @param {string} previewDivId - ID of preview div element
 * @param {string} uploadType - Type of upload (team, blog, gallery, etc.)
 */
function initFileUpload(fileInputId, pathInputId, previewDivId, uploadType) {
    const fileInput = document.getElementById(fileInputId);
    const pathInput = document.getElementById(pathInputId);
    const previewDiv = document.getElementById(previewDivId);

    if (fileInput && pathInput && previewDiv) {
        fileInput.addEventListener('change', function () {
            handleFileUpload(this, pathInput, previewDiv, uploadType);
        });
    }
}
