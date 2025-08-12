<?php
require_once '../../auth/auth_admin.php';
require_once '../../classes/activities.php';
require_once '../../classes/pagination_helper.php';

$db = new Database();
$conn = $db->connect();
$activity = new Activities($conn);

// ตั้งค่าพื้นฐาน
$currentPage   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage  = 10;

// รับค่าการค้นหา
$keyword    = trim($_GET['keyword'] ?? '');
$start_date = $_GET['start_date'] ?? '';
$end_date   = $_GET['end_date'] ?? '';

// ถ้ามีการกรองข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'GET' && (!empty($keyword) || !empty($start_date) || !empty($end_date))) {

    // นับจำนวนรายการที่ตรงกับเงื่อนไขการค้นหา
    $totalItems = $activity->getSearchAndFilterCount($keyword, $start_date, $end_date);

    // สร้าง pagination
    $pagination = new PaginationHelper($currentPage, $itemsPerPage, $totalItems);

    // ดึงข่าวตามเงื่อนไข
    $activity_list = $activity->searchAndFilterActivity($keyword,$start_date,$end_date,$pagination->getLimit(),$pagination->getOffset()
    );

} else {
    // นับจำนวนรายการทั้งหมด
    $totalItems = $activity->getTotalCount();

    // สร้าง pagination
    $pagination = new PaginationHelper($currentPage, $itemsPerPage, $totalItems);

    // ดึงข่าวทั้งหมด
    $activity_list = $activity->getAllactivity($pagination->getLimit(),$pagination->getOffset()
    );
}

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <?php include '../../includes/title.php'; ?>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../../assets/css/style_admin.css" rel="stylesheet">
    <link href="../../assets/css/sweetalert2.min.css" rel="stylesheet">
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

    <?php include '../includes/sidebar.php' ?>

    <div class="main-content">
        <!-- Heading -->
        <div class="d-flex">
            <h3 class="table-header mb-5"><b>จัดการข้อมูลกิจกรรม</b></h3>
            <div>
                <a href="add.php" class="btn btn-success mx-3">เพิ่มกิจกรรมใหม่</a>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <form method="GET" class="search-filter-section">
            <div class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">ค้นหา</label>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" value="<?php echo $keyword ?? null ?>" name="keyword" class="form-control" placeholder="ค้นหาหัวข้อข่าว และอื่นๆ...">
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-bold">วันที่เริ่มต้น</label>
                    <input type="date" name="start_date" value="<?php echo $start_date ?? null ?>" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-bold">วันที่สิ้นสุด</label>
                    <input type="date" name="end_date" value="<?php echo $end_date ?? null ?>" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
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
                            <th>หัวข้อ</th>
                            <th class="text-center">รูปภาพ</th>
                            <th>เขียนโดย</th>
                            <th>วันที่ลง</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (!empty($activity_list)) {
                            $i = 1;
                            foreach ($activity_list as $item) {
                                $id = $item['activity_id'];
                                $title = $item['title'];
                                $string_image = $item['image'];
                                $first_image = explode(",", $item['image'])[0];
                                $created_by = $item['first_name'] . ' ' . $item['last_name'];
                                $created_at = date('d/m/Y H:i', strtotime($item['created_at']));
                        ?>
                                <tr>
                                    <td>
                                        <?= $i++; ?>
                                    </td>
                                    <td><?= $title ?></td>
                                    <td class="text-center">
                                        <img src="../../assets/images/activity/<?= $first_image ?>" alt="<?= $title ?>">
                                    </td>
                                    <td><?= $created_by ?></td>
                                    <td><?= $created_at ?></td>
                                    <td class="text-center">
                                        
                                        <a class="btn btn-warning" href="edit.php?id=<?php echo $id ?>">แก้ไข</a>
                                        
                                        <button class="btn btn-danger" onclick="deleteData(<?php echo $id ?>, 'id=<?php echo $id  ?>&image=<?php echo $string_image ?>', 'delete.php')">
                                            ลบ
                                        </button>
                                    </td>
                                </tr>
                        <?php }
                        } ?>


                    </tbody>
                </table>
                <!--ภ้าไม่มีข้อมูลให้แสดงคำว่า ไม่พบข้อมูลข่าวสาร -->
                <?php if (empty($activity_list)) { ?>
                    <div class="text-center text-danger">ไม่พบข้อมูลกิจกรรม</div>
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



    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="../../assets/js/script_admin.js"></script>
    <script src="../../assets/js/sweetalert2.all.min.js"></script>
    <script src="../../assets/alerts/modal.js"></script>
    <script src="../functions/delete_data.js"></script>
</body>

</html>