function modalConfirm(title, text) {
    return Swal.fire({
        title: title,
        html: text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "ยกเลิก",
        confirmButtonText: "ใช่"
    });
}

function modalAlert(title, text, icon) {
    return Swal.fire({
        title: title,
        text: text,
        icon: icon
    });
}