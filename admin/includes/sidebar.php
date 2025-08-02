    <!-- Mobile Toggle Button -->
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="<?php echo $base_url ?>/admin/" class="logo">
                <img class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;" src="<?php echo $base_url; ?>/assets/images/logo/logo_department.png" alt="">
                <span>ระบบจัดการข้อมูล เว็บไซต์สายใยคอมพิวเตอร์ธุรกิจ วิทยาลัยเทคนิคอ่างทอง</span>
            </a>
        </div>

        <div class="sidebar-menu">
            <div class="menu-category">โปรไฟล์</div>
            <div class="menu-item">
                <div class="d-flex align-items-center profile">
                    <img src="<?php echo $base_url . '/assets/images/user/' .
                                    ($_SESSION['user']['user_type'] == USER_TYPE_ADMIN ? 'admin' : 'teacher') . '/' .
                                    $_SESSION['user']['image']; ?>"
                        alt="User" class="user-avatar me-3">
                    <div>
                        <div class="fw-bold"><?php echo $_SESSION['user']['fullname'] ?></div>
                        <small class="text-white"><?php echo $_SESSION['user']['position'] ?></small>
                    </div>
                </div>
            </div>

            <div class="menu-category">หลัก</div>

            <div class="menu-item">
                <a href="<?php echo $base_url ?>/admin/" class="menu-link active">
                    <i class="fas fa-home"></i>
                    <span>หน้าหลัก</span>
                </a>
            </div>

            <div class="menu-item">
                <a href="#" class="menu-link" data-bs-toggle="collapse" data-bs-target="#users-menu">
                    <i class="fas fa-users"></i>
                    <span>จัดการข้อมูลผู้ใช้งาน</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse submenu" id="users-menu">
                    <a href="<?php echo $base_url ?>/admin/user/student/" class="menu-link">
                        <i class="fas fa-user"></i>
                        <span>จัดการข้อมูลนักเรียน นักศึกษา</span>
                    </a>
                    <a href="<?php echo $base_url ?>/admin/user/alumni/" class="menu-link">
                        <i class="fas fa-user-graduate"></i>
                        <span>จัดการข้อมูลศิษย์เก่า</span>
                    </a>
                    <a href="<?php echo $base_url ?>/admin/user/teacher/" class="menu-link">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>จัดการข้อมูลครู/อาจารย์</span>
                    </a>
                    <a href="<?php echo $base_url ?>/admin/user/admin/" class="menu-link">
                        <i class="fas fa-user"></i>
                        <span>จัดการข้อมูลผู้ดูแลระบบ</span>
                    </a>
                    <a href="<?php echo $base_url ?>/admin/user/user_approval/" class="menu-link">
                        <i class="fas fa-user-plus"></i>
                        <span>คำขอลงทะเบียนใหม่</span>
                    </a>
                </div>
            </div>

            <div class="menu-item">
                <a href="#" class="menu-link" data-bs-toggle="collapse" data-bs-target="#content-menu">
                    <i class="fas fa-file-alt"></i>
                    <span>จัดการเนื้อหา</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse submenu" id="content-menu">
                    <a href="<?php echo $base_url ?>/admin/news/" class="menu-link">
                        <i class="fas fa-newspaper"></i>
                        <span>ข่าวสาร/ประชาสัมพันธ์</span>
                    </a>
                    <a href="<?php echo $base_url ?>/admin/activities/" class="menu-link">
                        <i class="fas fa-calendar"></i>
                        <span>กิจกรรม</span>
                    </a>
                    <a href="<?php echo $base_url ?>/admin/webboard/" class="menu-link">
                        <i class="fas fa-comments"></i>
                        <span>กระทู้</span>
                    </a>
                </div>
            </div>

            <div class="menu-category">รายงาน</div>

            <div class="menu-item">
                <a href="#" class="menu-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>สถิติการใช้งาน</span>
                </a>
            </div>

            <div class="menu-item">
                <a href="#" class="menu-link" data-bs-toggle="collapse" data-bs-target="#report-menu">
                    <i class="fas fa-question"></i>
                    <span>รายงานการใช้งานที่ไม่เหมาะสม</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse submenu" id="report-menu">
                    <a href="<?php echo $base_url ?>/admin/report_problems/topic/" class="menu-link">
                        <i class="fas fa-newspaper"></i>
                        <span>รายงานกระทู้</span>
                    </a>
                    <a href="<?php echo $base_url ?>/admin/report_problems/comment/" class="menu-link">
                        <i class="fas fa-calendar"></i>
                        <span>รายงานความคิดเห็น</span>
                    </a>
                </div>
            </div>

            <div class="menu-category">ระบบ</div>

            <div class="menu-item">
                <a href="<?php echo $base_url ?>/admin/setting.php" class="menu-link">
                    <i class="fas fa-cog"></i>
                    <span>ตั้งค่าเว็บไซต์</span>
                </a>
            </div>

            <div class="menu-item">
                <a href="#" class="menu-link">
                    <i class="fas fa-database"></i>
                    <span>สำรองข้อมูล</span>
                </a>
            </div>

            <div class="menu-item">
                <a href="<?php echo $base_url ?>/auth/logout.php" class="menu-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>ออกจากระบบ</span>
                </a>
            </div>
        </div>
    </div>