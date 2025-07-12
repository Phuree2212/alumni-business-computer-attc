// Multiple image preview function for new images
function previewImages(input) {
    const container = document.getElementById('imagePreviewContainer');
    container.innerHTML = '';

    if (input.files) {
        // Get current existing images count
        const existingImagesCount = document.querySelectorAll('.existing-image-container').length;
        const newImagesCount = input.files.length;
        const totalCount = existingImagesCount + newImagesCount;

        // Validate total file count
        if (totalCount > 5) {
            Swal.fire({
                title: 'ข้อผิดพลาด!',
                text: `สามารถมีรูปภาพได้สูงสุด 5 รูปเท่านั้น (ปัจจุบันมี ${existingImagesCount} รูป)`,
                icon: 'error'
            });
            input.value = '';
            return;
        }

        Array.from(input.files).forEach((file, index) => {
            // Validate file size
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({
                    title: 'ข้อผิดพลาด!',
                    text: `ไฟล์ ${file.name} มีขนาดใหญ่เกิน 5MB`,
                    icon: 'error'
                });
                return;
            }

            // Validate file type
            if (!file.type.match('image.*')) {
                Swal.fire({
                    title: 'ข้อผิดพลาด!',
                    text: `ไฟล์ ${file.name} ไม่ใช่ไฟล์รูปภาพ`,
                    icon: 'error'
                });
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                const previewItem = document.createElement('div');
                previewItem.className = 'preview-item';
                previewItem.innerHTML = `
                            <img src="${e.target.result}" alt="Preview ${index + 1}">
                            <div class="text-center small mt-1">${file.name}</div>
                        `;
                container.appendChild(previewItem);
            };
            reader.readAsDataURL(file);
        });
    }
}