<?php
require_once '../../../auth/auth_admin.php';
require_once '../../../classes/user.php';
require_once '../../../classes/pagination_helper.php';

$db = new Database();
$conn = $db->connect();

$user_approval = new UserApproval($conn);

// ตั้งค่าพื้นฐาน
$currentPage   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage  = 10;

// รับค่าการค้นหา
$keyword    = trim($_GET['keyword'] ?? '');
$user_type = $_GET['user_type'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date   = $_GET['end_date'] ?? '';

// ถ้ามีการกรองข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'GET' && (!empty($keyword) || !empty($user_type) || !empty($start_date) || !empty($end_date))) {

    // นับจำนวนรายการที่ตรงกับเงื่อนไขการค้นหา
    $totalItems = $user_approval->getSearchAndFilterCount($keyword, $user_type, $start_date, $end_date);

    // สร้าง pagination
    $pagination = new PaginationHelper($currentPage, $itemsPerPage, $totalItems);

    // ดึงข่าวตามเงื่อนไข
    $user_approval_list = $user_approval->searchAndFilterUserRegister(
        $keyword,
        $user_type,
        $start_date,
        $end_date,
        $pagination->getLimit(),
        $pagination->getOffset()
    );
} else {
    // นับจำนวนรายการทั้งหมด
    $totalItems = $user_approval->getTotalCount();

    // สร้าง pagination
    $pagination = new PaginationHelper($currentPage, $itemsPerPage, $totalItems);

    // ดึงข่าวทั้งหมด
    $user_approval_list = $user_approval->getRegisterWaiingApprove($pagination->getLimit(), $pagination->getOffset());
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
<style>
    .search-filter-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        padding-left: 2.5rem;
        border-radius: 10px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .search-box .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .filter-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .table img {
        max-width: 200px;
        min-width: 100px;
    }
</style>

<body>

    <?php include '../../includes/sidebar.php' ?>

    <div class="main-content">
        <!-- Heading -->
        <div class="d-flex">
            <h3 class="table-header mb-5"><b>รายชื่อผู้ลงทะเบียนใหม่</b></h3>
        </div>

        <!-- Search and Filter Section -->
        <form method="GET" class="search-filter-section">
            <div class="row align-items-end">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">ค้นหา</label>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" value="<?php echo $keyword ?? null ?>" name="keyword" class="form-control" placeholder="ค้นหารายชื่อ และอื่นๆ...">
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-bold">ประเภทผู้ใช้</label>
                    <select name="user_type" class="form-select">
                        <option selected>ทั้งหมด</option>
                        <option value="alumni">ศิษย์เก่า</option>
                        <option value="student">ศิษย์ปัจจุบัน</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-bold">วันที่เริ่มต้น</label>
                    <input type="date" name="start_date" value="<?php echo $start_date ?? null ?>" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-bold">วันที่สิ้นสุด</label>
                    <input type="date" name="end_date" value="<?php echo $end_date ?? null ?>" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>ค้นหา
                        </button>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-redo me-1"></i>รีเซ็ต
                        </a>
                    </div>
                </div>
            </div>
        </form>



        <!-- Tables List -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ลำดับที่</th>
                            <th>รหัสนักศึกษา</th>
                            <th class="text-center">ชื่อ-นามสกุล</th>
                            <th>อิเมลล์</th>
                            <th>เบอร์โทรศัพท์</th>
                            <th>ประเภทผู้ใช้</th>
                            <th>ระดับชั้นที่ศึกษาปัจจุบัน / ระดับชั้นที่จบการศึกษา</th>
                            <th>ปีที่จบการศึกษา</th>
                            <th>สถานะการสมัครสมาชิก</th>
                            <th>วันที่ลงทะเบียน</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (!empty($user_approval_list)) {
                            $i = 1;
                            foreach ($user_approval_list as $item) {
                                $id = $item['user_id'];
                                $student_code = $item['student_code'];
                                $full_name = $item['first_name'] . ' ' . $item['last_name'];
                                $email = $item['email'];
                                $phone = $item['phone'];
                                $user_type = $item['user_type'];
                                $education_level = $item['education_level'];
                                $graduation_year = $item['graduation_year'] == 0 ? 'นักศึกษาปัจจุบัน' : $item['graduation_year'];
                                $status_register = $item['status_register'] == 2 ? 'รอดำเนินการตรวจสอบ' : '';
                                $created_at = date('d/m/Y H:i', strtotime($item['created_at']));
                        ?>
                                <tr>
                                    <td>
                                        <?= $i++; ?>
                                    </td>
                                    <td class="text-center"><?php echo $student_code; ?></td>
                                    <td><?php echo $full_name; ?></td>
                                    <td><?php echo $email; ?></td>
                                    <td><?php echo $phone; ?></td>
                                    <td><?php echo $user_type; ?></td>
                                    <td><?php echo $education_level; ?></td>
                                    <td><?php echo $graduation_year; ?></td>
                                    <td><?php echo $status_register; ?></td>
                                    <td><?php echo $created_at; ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-success" onclick="approveUser(<?php echo $id ?>)">อนุมัติ</button>
                                        <button class="btn btn-danger" onclick="rejectUser(<?php echo $id ?>)">ไม่อนุมัติ</button>
                                    </td>
                                </tr>
                        <?php }
                        } ?>


                    </tbody>
                </table>
                <!--ภ้าไม่มีข้อมูลให้แสดงคำว่า ไม่พบข้อมูลข่าวสาร -->
                <?php if (empty($user_approval_list)) { ?>
                    <div class="text-center text-danger">ไม่พบข้อมูลผู้ลงทะเบียนใหม่</div>
                <?php } ?>

            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            <div class="row align-items-center">
                <?php

                echo $pagination->renderBootstrap();

                ?>
            </div>
        </div>
    </div>



    <script src="../../../assets/js/bootstrap.bundle.js"></script>
    <script src="../../../assets/js/script_admin.js"></script>
    <script src="../../../assets/js/sweetalert2.all.min.js"></script>
    <script src="../../../assets/alerts/modal.js"></script>
    <script>
        function approveUser(userId) {
            return modalConfirm('อนุมัติการลงทะเบียนผู้ใช้', `ยืนยันการอนุมัติลงทะเบียนบัญชีผู้ใช้ที่ ID ${userId} ใช่ไหม?`)
                .then((result) => {
                    if (result.isConfirmed) {
                        fetch('approve.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: 'id=' + encodeURIComponent(userId)
                            })
                            .then(response => response.json())
                            .then(response => {

                                if (response.result) {
                                    modalAlert(`อนุมัติบัญชีผู้ใช้สำเร็จ`, `อนุมัติบัญชีผู้ใช้ ID ${userId} สำเร็จ`, 'success')
                                        .then(() => {
                                            location.reload();
                                        })
                                } else {
                                    modalAlert(`อนุมัติบัญชีผู้ใช้ไม่สำเร็จ`, response.message, 'error')
                                }
                            })
                            .catch(error => {
                                modalAlert('การเชื่อมต่อล้มเหลว', 'ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้', 'error');
                                console.error('Fetch error:', error);
                            });
                    }

                })
        }

        function rejectUser(userId) {
            return modalConfirm('ปฏิเสธการลงทะเบียนผู้ใช้', `ปฏิเสธการลงทะเบียนผู้ใช้ ID ${userId} ใช่ไหม?`)
                .then((result) => {
                    if (result.isConfirmed) {
                        fetch('reject.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: 'id=' + encodeURIComponent(userId)
                            })
                            .then(response => response.json())
                            .then(response => {

                                if (response.result) {
                                    modalAlert(`ปฏิเสธการอนุมัติบัญชีผู้ใช้สำเร็จ`, `ปฎิเสธการอนุมัติบัญชีผู้ใช้ ID ${userId} สำเร็จ`, 'success')
                                        .then(() => {
                                            location.reload();
                                        })
                                } else {
                                    modalAlert(`ปฏิเสธการอนุมัติบัญชีผู้ใช้ไม่สำเร็จ`, response.message, 'error')
                                }
                            })
                            .catch(error => {
                                modalAlert('การเชื่อมต่อล้มเหลว', 'ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้', 'error');
                                console.error('Fetch error:', error);
                            });
                    }

                })
        }
    </script>
</body>

</html>