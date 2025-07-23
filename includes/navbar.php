<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light" style="top: 0; position: sticky; z-index: 9999;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo $base_url ?>/public/">
            <img class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;" src="<?php echo $base_url; ?>/assets/images/logo/logo_department.png" alt="">
            <div>
                <div class="fw-bold text-primary">สายใยคอมพิวเตอร์ธุรกิจ</div>
                <small class="text-muted">วิทยาลัยเทคนิคอ่างทอง</small>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?php echo $base_url ?>/public/">หน้าแรก</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $base_url ?>/public/news/">ข่าวสาร</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $base_url ?>/public/activities/">กิจกรรม</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $base_url ?>/public/gallery.php">รูปภาพ</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $base_url ?>/public/webboard/">เว็บบอร์ด</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $base_url ?>/public/alumni/">ศิษย์เก่า</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $base_url ?>/public/about.php">เกี่ยวกับเรา</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        ระบบสมาชิก
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <?php if(isset($_SESSION['user']['user_type']) && ($_SESSION['user']['user_type'] == USER_TYPE_ALUMNI || $_SESSION['user']['user_type'] == USER_TYPE_STUDENT)){ ?>
                            <li><a class="dropdown-item" href="<?php echo $base_url ?>/public/profile.php">โปรไฟล์</a></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo $base_url ?>/auth/logout.php">ออกจากจะบบ</a></li>
                        <?php }else{ ?>
                        <li><a class="dropdown-item" href="<?php echo $base_url ?>/public/login.php">เข้าสู่ระบบ</a></li>
                        <li><a class="dropdown-item" href="<?php echo $base_url ?>/public/register.php">สมัครสมาขิก</a></li>
                        <li><a class="dropdown-item text-success" href="<?php echo $base_url ?>/public/check_status_user.php">ตรวจสอบสถานะการสมัครสมาชิก</a></li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>