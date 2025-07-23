<?php
require_once '../config/config.php';
require_once '../classes/auth.php';
require_once '../classes/user.php';

$db = new Database();
$conn = $db->connect();

$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $data = $_POST['data'] ?? '';

    $result = $user->checkStatusRegisterUser($data);

    // ตั้งค่าหัวข้อว่าเป็น JSON
    header('Content-Type: application/json');

    echo json_encode($result);
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <?php include '../includes/title.php' ?>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/sweetalert2.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
</head>

<body>
    <?php include '../includes/navbar.php' ?>

    <!-- ฟอร์มเข้าสู่ระบบ -->
    <div class="bg-light py-3 py-md-5">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-11 col-lg-8 col-xl-7 col-xxl-6">
                    <div class="bg-white p-4 p-md-5 rounded shadow-sm">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-5">
                                    <h3 class="h3 text-center text-success">ตรวจสอบสถานะการลงทะเบียน</h3>
                                </div>
                            </div>
                        </div>

                        <form id="formCheckStatusUser" method="post">
                            <div class="row gy-3 gy-md-4 overflow-hidden">
                                <div class="col-12">
                                    <label for="text" class="form-label">กรอกรหัสนักศึกษา หรือ ชื่อ นามสกุล ของท่านเพื่อตรวจสอบสถานะการลงทะเบียน<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="data" id="data" placeholder="รหัสนักศึกษา หรือ ชื่อจริง นามสกุล เช่น สมชาย ใจบุญ" required>
                                </div>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <button class="btn btn-lg btn-primary" type="submit">ตรวจสอบสถานะ</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php' ?>

    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/sweetalert2.all.min.js"></script>
    <script src="../assets/alerts/modal.js"></script>
    <script src="function/validate_form.js"></script>
    <script>
        document.getElementById('formCheckStatusUser').addEventListener('submit', function(e) {
            e.preventDefault();

            const data = document.getElementById('data').value;

            const formData = new FormData(this);
            const body = new URLSearchParams(formData).toString()

            fetch('check_status_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: body
                })
                .then(response => response.json())
                .then(response => {
                    if (response.result === true) {
                        modalAlert('สถานะการสมัครสมาชิก', response.message, 'success')
                        .then(()=>{
                            window.location.href = 'login.php';
                        })
                        
                    } else {
                        modalAlert('สถานะการสมัครสมาชิก', response.message, 'error');
                    }
                })
                .catch(error => {
                    modalAlert('การเชื่อมต่อล้มเหลว', 'ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้', 'error');
                    console.error('Fetch error:', error);
                });

        });
    </script>
</body>

</html>