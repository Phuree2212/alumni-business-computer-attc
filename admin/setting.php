<?php
require_once '../auth/auth_admin.php';
//require_once '../classes/setting.php';


?>
<!DOCTYPE html>
<html lang="th">

<head>
    <?php include '../includes/title.php'; ?>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../assets/css/style_admin.css" rel="stylesheet">
    <link href="../assets/css/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<style>
    .settings-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .settings-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 8px 8px 0 0;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
    }

    .btn-primary {
        background: #667eea;
        border-color: #667eea;
    }

    .btn-primary:hover {
        background: #5a6fd8;
        border-color: #5a6fd8;
    }

    .color-preview {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #dee2e6;
        display: inline-block;
        margin-left: 10px;
    }

    .section-title {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
</style>

<body>

    <?php include 'includes/sidebar.php' ?>

    <!-- Main Content -->
    <div class="main-content">

        <!-- header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">การตั้งค่าเว็บไซต์</h1>
                <p class="text-muted">จัดการการตั้งค่าทั่วไปของเว็บไซต์ระบบศิษย์เก่า</p>
            </div>
        </div>

        <form action="">
            <div class="mb-3">
                <label class="form-label">ชื่อเว็บไซต์</label>
                <input type="text" class="form-control" placeholder="ชื่อเว็บไซต์">
            </div>
            <div class="mb-3">
                <label class="form-label">คำอธิบายเว็บไซต์</label>
                <textarea class="form-control" rows="3" placeholder="คำอธิบายเว็บไซต์"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">ภาษาเริ่มต้น</label>
                <select class="form-select">
                    <option value="th" selected>ภาษาไทย</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">โซนเวลา</label>
                <select class="form-select">
                    <option value="Asia/Bangkok" selected>Asia/Bangkok (UTC+7)</option>
                    <option value="Asia/Jakarta">Asia/Jakarta (UTC+7)</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">โลโก้เว็บไซต์</label>
                <input class="form-control" type="file" accept="image/*">
                <div class="form-text">แนะนำขนาด 200x60 พิกเซล</div>
            </div>
            <div class="mb-3">
                <label class="form-label">ไอคอน (Favicon)</label>
                <input class="form-control" type="file" accept="image/*">
                <div class="form-text">แนะนำขนาด 32x32 พิกเซล</div>
            </div>
            <div class="mb-3">
                <label class="form-label">เว็บไซต์หลักวิทยาลัย</label>
                <input type="url" class="form-control" placeholder="https://www.university.ac.th">
            </div>
            <div class="d-flex justify-content-end gap-2 mt-4">
                <button class="btn btn-danger">ยกเลิก</button>
                <button class="btn btn-success">บันทึกการตั้งค่า</button>
            </div>
        </form>
    </div>

    <script src="../assets/js/bootstrap.bundle.js"></script>
    <script src="../assets/js/script_admin.js"></script>
    <script src="../assets/js/sweetalert2.all.min.js"></script>
    <script src="../assets/alerts/modal.js"></script>
</body>

</html>