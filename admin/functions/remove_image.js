let deletedImages = [];

// Function to remove existing image
function removeExistingImage(button, imageName) {
    Swal.fire({
        title: 'คุณต้องการลบรูปภาพนี้?',
        text: "การลบแล้วจะไม่สามารถกู้คืนได้",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่, ลบเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // Add to deleted images array
            deletedImages.push(imageName);

            // Update hidden input
            document.getElementById('deletedImages').value = deletedImages.join(',');

            // Remove from DOM
            button.closest('.existing-image-container').remove();

            Swal.fire(
                'ลบแล้ว!',
                'รูปภาพถูกลบเรียบร้อยแล้ว',
                'success'
            );
        }
    });
}