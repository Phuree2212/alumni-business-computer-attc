function createData(formData, pathToApi) {
    return modalConfirm('ยืนยันการสร้างบัญชีผู้ดูแลระบบใหม่ใช่หรือไม่?').
        then((result) => {
            if (result.isConfirmed) {
                fetch(pathToApi, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(response => {
                        if (response.result === true) {
                            modalAlert('สร้างบัญชีใหม่สำเร็จ', 'สร้างบัญชีผู้ดูแลระบบสำเร็จ', 'success')
                                .then(() => window.location.href = 'index.php');
                        } else {
                            modalAlert('สร้างบัญชีไม่สำเร็จ', response.message, 'error');
                        }
                    })
                    .catch(error => {
                        modalAlert('การเชื่อมต่อล้มเหลว', 'ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้', 'error');
                        console.error('Fetch error:', error);
                    });
            }
        }

        )
}
