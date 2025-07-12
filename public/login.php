<?php
require_once '../config/config.php';
require_once '../classes/user.php';

$db = new Database();
$conn = $db->connect();

$user = new User($conn);

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $student_code = $_POST['student_code'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = $user->login($student_code, $password);

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

    <!-- ฟอร์มเข้าสู่ระบบ -->>
    <div class="bg-light py-3 py-md-5">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-11 col-lg-8 col-xl-7 col-xxl-6">
                    <div class="bg-white p-4 p-md-5 rounded shadow-sm">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-5">
                                    <h3 class="h3 text-center">เข้าสู่ระบบ ศิษย์เก่า/ศิษย์ปัจจุบัน</h3>
                                </div>
                            </div>
                        </div>

                        <form id="formLogin" method="post">
                            <div class="row gy-3 gy-md-4 overflow-hidden">
                                <div class="col-12">
                                    <label for="firstName" class="form-label">รหัสนักศึกษา<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="student_code" id="student_code" placeholder="รหัสนักศึกษา" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-12">
                                    <label for="password" class="form-label">รหัสผ่าน<span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password" id="password" value="" required>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <button class="btn btn-lg btn-primary" type="submit">เข้าสู่ระบบ</button>
                                    </div>
                                </div>
                            </div>
                        </form>



                        <div class="row">
                            <div class="col-12">
                                <hr class="mt-5 mb-4 border-secondary-subtle">
                                <div class="col-12">
                                    <p class="m-0 text-secondary text-center">คุณยังไม่ได้ลงทะเบียนใช่ไหม? <a href="register.php" class="link-primary text-decoration-none">ลงทะเบียน</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php' ?>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sweetalert2.all.min.js"></script>
    <script src="../assets/alerts/modal.js"></script>
    <script src="function/validate_form.js"></script>
    <script>
        document.getElementById('formLogin').addEventListener('submit', function(e) {
            e.preventDefault();

            const studentCode = document.getElementById('student_code').value;
            const password = document.getElementById('password').value;

            const formData = new FormData(this);
            const body = new URLSearchParams(formData).toString()

            fetch('login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: body
                })
                .then(response => response.json())
                .then(result => {
                    if (result.result === true) {
                        window.location.href='index.php';
                    } else {
                        modalAlert('เข้าสู่ระบบไม่สำเร็จ', result.message, 'error');
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