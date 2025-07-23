<?php
require_once '../config/config.php';
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <?php include '../includes/title.php' ?>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
</head>

<style>
    .about-container {
        min-height: calc(100vh - 3.5rem);
        background-color: #f8f9fa;
    }

    .hero-section {
        background: linear-gradient(135deg, #467bcb 0%, #5a8dd8 100%);
        color: white;
        padding: 4rem 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.1"><polygon points="0,100 1000,0 1000,100"/></svg>');
        background-size: cover;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .main-content {
        background-color: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1);
        padding: 2rem;
        min-height: calc(100vh - 8rem);
    }

    .content-section {
        background-color: #fff;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 2.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #467bcb;
    }

    .section-title {
        color: #1f2937;
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }

    .section-title i {
        color: #467bcb;
        margin-right: 0.75rem;
        font-size: 1.5rem;
    }

    .logo-department img {
        max-width: 100%;
    }

    .developer-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        margin: 1rem 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid rgba(70, 123, 203, 0.1);
    }
</style>

<body>
    <?php include '../includes/navbar.php' ?>

    <div class="about-container">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <h1 class="display-4 fw-bold mb-4">
                        <i class="fas fa-info-circle me-3"></i>
                        เกี่ยวกับเรา
                    </h1>
                </div>
            </div>
        </section>

        <div class="container">
            <div class="main-content my-4">
                <div class="row">
                    <div class="logo-department col-lg-4 text-center">
                        <img src="../assets/images/logo/logo_department.png" alt="โลโก้แผนกวิชาคอมพิวเตอร์ธุรกิจ วิทยาลัยเทคนิคอ่างทอง">
                    </div>
                    <div class="col-lg-8 py-5">
                        <div class="d-flex mb-3 flex-column justify-content-center">
                            <h3>แผนกวิชาคอมพิวเตอร์ธุรกิจ วิทยาลัยเทคนิคอ่างทอง</h3>
                            <h5>เว็บไซต์สายใยคอมพิวเตอร์ธุรกิจ จัดทำขึ้นเพื่อเป็นศูนย์กลางในการเชื่อมโยงระหว่าง ศิษย์เก่า, ศิษย์ปัจจุบัน, และ แผนกวิชาคอมพิวเตอร์ธุรกิจ วิทยาลัยเทคนิคอ่างทอง โดยมีเป้าหมายเพื่อส่งเสริมการสื่อสาร การแลกเปลี่ยนความรู้ และการสร้างเครือข่ายความสัมพันธ์ระหว่างกันอย่างยั่งยืน</h5>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <h3>วัตถุประสงค์</h3>
                            <h5>
                                <ul>
                                    <li>เป็นช่องทางในการเผยแพร่ข่าวสาร กิจกรรม และประชาสัมพันธ์ของแผนกวิชา</li>
                                    <li>สร้างสังคมการเรียนรู้และการสนับสนุนซึ่งกันและกันระหว่างสมาชิก</li>
                                    <li>เปิดพื้นที่สำหรับศิษย์เก่าในการติดต่อสื่อสาร และกลับมามีส่วนร่วมกับแผนกวิชา</li>
                                </ul>
                            </h5>

                        </div>
                    </div>
                </div>
                <hr>

                <!--ผู้จัดทำ-->
                <div class="row">
                    <div class="col-lg-6">
                        <h2 class="section-title justify-content-center">
                            <i class="fas fa-users-cog"></i>
                            ผู้จัดทำ
                        </h2>
                        <div class="developer-card text-center">
                            <div class="developer-avatar mx-auto">
                                <i class="fas fa-user"></i>
                            </div>
                            <h4 class="developer-name">นายคนที่ 1</h4>
                            <p class="developer-role">Project Manager & Full-Stack Developer</p>
                            <p class="developer-description">
                                รับผิดชอบการออกแบบระบบ การพัฒนา Frontend และ Backend รวมถึงการประสานงานทีม
                            </p>
                            <div class="contact-info">
                                <div><i class="fas fa-envelope"></i> email1@example.com</div>
                                <div><i class="fas fa-id-card"></i> รหัสนักศึกษา: 65XXXXXX</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <h2 class="section-title justify-content-center">
                            <i class="fas fa-users-cog"></i>
                            ครูที่ปรึกษาโครงงาน
                        </h2>
                        <div class="developer-card text-center">
                            <div class="developer-avatar mx-auto">
                                <i class="fas fa-user"></i>
                            </div>
                            <h4 class="developer-name">นายคนที่ 1</h4>
                            <p class="developer-role">Project Manager & Full-Stack Developer</p>
                            <p class="developer-description">
                                รับผิดชอบการออกแบบระบบ การพัฒนา Frontend และ Backend รวมถึงการประสานงานทีม
                            </p>
                            <div class="contact-info">
                                <div><i class="fas fa-envelope"></i> email1@example.com</div>
                                <div><i class="fas fa-id-card"></i> รหัสนักศึกษา: 65XXXXXX</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <div class="developer-card">
                        <h5 class="section-title justify-content-center">
                            <i class="fas fa-heart"></i>
                            ขอบคุณ
                        </h5>
                        <p class="text-muted">
                            ขอขอบคุณทุกท่านที่ให้การสนับสนุนและช่วยเหลือในการพัฒนาเว็บไซต์สายใยคอมพิวเตอร์ธุรกิจ 
                            หากมีข้อเสนะแนะหรือพบปัญหาการใช้งาน สามารถติดต่อทีมพัฒนาได้ตลอดเวลา
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>
                            พัฒนาเมื่อ: ปี พ.ศ. 2568 | 
                            <i class="fas fa-code me-2 ms-3"></i>
                            Version 1.0
                        </p>
                    </div>
                </div>


            </div>
        </div>

    </div>

    <?php include '../includes/footer.php' ?>

    <script src="../assets/js/bootstrap.min.js"></script>

</body>

</html>