function deleteData(id, formParams, pathToApi) {
    return modalConfirm("คุณต้องการลบข้อมูลนี้ใช่หรือไม่?", "เมื่อทำการลบข้อมูลแล้วจะไม่สามารถกู้คืนกลับได้")
        .then((result) => {
            if (result.isConfirmed) {
                fetch(pathToApi, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: formParams.toString()
                })
                    .then(response => response.json())
                    .then(response => {
                        if (response.result) {
                            modalAlert(`ลบข้อมูล ID ที่ ${id} สำเร็จ`, "ข้อมูลได้ถูกลบเรียบร้อยแล้ว", "success")
                            .then(() => {
                                location.reload();
                            });
                        } else {
                            modalAlert(`เกิดข้อผิดพลาด`, "มีบางอย่างผิดพลาด กรุณาลองใหม่อีกครั้ง", "error")
                        }
                    })
                    .catch(error => {
                        modalAlert(`การเชื่อมต่อล้มเหลว`, "ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้", "error")
                        console.error('Fetch error:', error);
                    });
            }
        });
}
