<?php
include '../../../config/config.php';
require_once '../../../classes/admin.php';
require_once '../../../classes/image_uploader.php';

$db = new Database();
$conn = $db->connect();

$Teacher = new Admin($conn, 'teacher');

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../../../assets/css/style_admin.css" rel="stylesheet">
    <link href="../../../assets/css/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body>

    <?php include '../../includes/sidebar.php' ?>

    <div class="main-content">
        <form id="editForm" enctype="multipart/form-data">
            <div class="card-body">
                <h3 class="h3 mb-4 text-success">สร้างข้อมูล ครู/อาจารย์ ใหม่</h3>
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
                            <input type="email" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">รหัสผ่าน</label>
                                <input type="text" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ยืนยันรหัสผ่าน</label>
                                <input type="text" class="form-control" id="confirmPassword" name="confirm_password" required>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ชื่อ</label>
                                <input type="text" class="form-control" id="firstName" name="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">นามสกุล</label>
                                <input type="text" class="form-control" id="lastName" name="last_name" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">อีเมล</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">เบอร์โทรศัพท์</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>

                            <div class="col-md-6 mb-5">
                                <label class="form-label">ตำแหน่ง</label>
                                <select class="form-select" name="position" id="position" required>
                                    <option value="">เลือก</option>
                                    <option value="หัวหน้าแผนกวิชา">หัวหน้าแผนกวิชา</option>
                                    <option value="ครูผู้ช่วย">ครูผู้ช่วย</option>
                                    <option value="ครูอัตราจ้าง">ครูอัตราจ้าง</option>
                                </select>
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
    <script>
        //แสดงภาพตัวอย่างเมื่อเลือกรูปโปรไฟล์
        const input = document.getElementById('profilePic');

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
    </script>
</body>

</html>