function updateData(id, formParams, pathToApi) {
    return modalConfirm('ยืนยันการแก้ไขข้อมูล', `ยืนยันการแก้ไขข้อมูล ID ${id}`)
        .then((result) => {
            if (result.isConfirmed) {
                fetch(pathToApi, {
                    method: 'POST',
                    body: formParams
                })
                    .then(response => response.json())
                    .then(response => {
                        if (response.result) {
                            modalAlert(`อัปเดตข้อมูล ID ${id} สำเร็จ`, "ข้อมูลได้ถูกอัปเดตสำเร็จ", "success")
                                .then(() => {
                                    location.reload();
                                });
                        } else {
                            modalAlert(`อัปเดตข้อมูล ID ${id} ไม่สำเร็จ`, `${response.message}`, "error")
                        }
                    })
                    .catch(error => {
                        modalAlert(`การเชื่อมต่อล้มเหลว`, "ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้", "error")
                        console.error('Fetch error:', error);
                    });
                    
            }
        });
}
