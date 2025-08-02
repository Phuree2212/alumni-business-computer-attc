<?php
require_once '../../../auth/auth_admin.php';
require_once '../../../classes/admin.php';
require_once '../../../classes/image_uploader.php';

$db = new Database();
$conn = $db->connect();

$teacher = new Admin($conn, 'admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];

    $image = $_FILES['image'];

    $result = $teacher->create($username, $password, $email, $first_name, $last_name, $phone, $position, $image);

    echo json_encode($result);

    exit;
}

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <?php include '../../../includes/title.php'; ?>
    <link href="../../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../../../assets/css/style_admin.css" rel="stylesheet">
    <link href="../../../assets/css/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body>

    <?php include '../../includes/sidebar.php' ?>

    <div class="main-content">
        <form id="createForm" enctype="multipart/form-data">
            <div class="card-body">
                <h3 class="h3 mb-4 text-success">สร้างข้อมูล ผู้ดูแลระบบ ใหม่</h3>
                <div class="row">
                    <div class="col-md-2">
                        <img id="imagePreview" src="../../../assets/images/user/no-image-profile.jpg" class="img-thumbnail mb-2" style="width: 200px; object-fit: cover;">
                        <br>
                        <label class="btn btn-primary">
                            เลือกรูปภาพ
                            <input type="file" name="image" id="profilePic" accept="image/*" hidden>
                        </label>
                        <small class="text-muted d-block mt-2">
                            รองรับ JPG, PNG, GIF<br>
                            ขนาดไม่เกิน 5MB
                        </small>
                    </div>

                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">ชื่อผู้ใช้งาน</label>
                            <input type="username" class="form-control" id="username" name="username">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">รหัสผ่าน</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ยืนยันรหัสผ่าน</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password">
                                <div class="invalid-feedback"></div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ชื่อ</label>
                                <input type="text" class="form-control" id="firstName" name="first_name">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">นามสกุล</label>
                                <input type="text" class="form-control" id="lastName" name="last_name">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">อีเมล</label>
                            <input type="email" class="form-control" id="email" name="email">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">เบอร์โทรศัพท์</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-5">
                                <label class="form-label">ตำแหน่ง</label>
                                <select class="form-select" name="position" id="position" readonly>
                                    <option value="ผู้ดูแลระบบ">ผู้ดูแลระบบ</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                    </div>
                    <hr>
                    <div class="mt-3 text-center">
                        <button type="submit" name="create_teacher" class="btn btn-success">สร้างบัญชีใหม่</button>
                        <a href="index.php" class="btn btn-danger">ยกเลิก</a>
                    </div>
                </div>

        </form>
    </div>
    </div>



    <script src="../../../assets/js/bootstrap.bundle.js"></script>
    <script src="../../../assets/js/sweetalert2.all.min.js"></script>
    <script src="../../../assets/js/script_admin.js"></script>
    <script src="../../../assets/alerts/modal.js"></script>
    <script src="../../../assets/js/function/validate_form.js"></script>
    <script src="../../functions/create_data.js"></script>
    <script>
        const input = document.getElementById('profilePic');

        //แสดงภาพตัวอย่างเมื่อเลือกรูปโปรไฟล์
        input.addEventListener('change', function() {
            const file = this.files[0];

            const preview = document.getElementById('imagePreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        //ตรวจสอบความถูกต้องของฟอร์ม
        const form = document.getElementById('createForm');

        function validateForm() {
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);

            let isValid = true;

            if (!data.username) {
                showFieldError('username', 'กรุณากรอกชื่อผู้ใช้งาน');
                isValid = false;
            }

            if (!data.first_name) {
                showFieldError('firstName', 'กรุณากรอกชื่อ');
                isValid = false;
            }

            if (!data.last_name) {
                showFieldError('lastName', 'กรุณากรอกนามสกุล');
                isValid = false;
            }

            if (!validatePassword(data.password)) {
                showFieldError('password', 'รหัสผ่านต้องมีมากกว่า 6 ตัวอักษร');
                isValid = false;
            }

            if (data.password != data.confirm_password) {
                showFieldError('confirmPassword', 'การยืนยันรหัสผ่านไม่ตรงกัน');
                isValid = false;
            }

            if (!validatePassword(data.email)) {
                showFieldError('email', 'รูปแบบอิเมลไม่ถูกต้อง');
                isValid = false;
            }

            if (!validatePassword(data.phone)) {
                showFieldError('phone', 'กรุณากรอกรหัสผ่าน');
                isValid = false;
            }

            if (!data.position || data.position === "") {
                showFieldError('position', 'กรุณาเลือกตำแหน่ง');
                isValid = false;
            }

            return isValid;
        }

        form.addEventListener('submit', (e) => {
            e.preventDefault();

            clearValidation('createForm');

            const formData = new FormData(form);

            if (validateForm()) {
                createData(formData, 'add.php');
            }
        })
    </script>
</body>

</html>