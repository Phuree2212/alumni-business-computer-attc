<?php
require_once '../../config/config.php';
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
    $activity_list = $activity->getAllActivity($pagination->getLimit(),$pagination->getOffset()
    );
}

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <?php include '../../includes/title.php' ?>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../../assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
</head>
<style>
    .section-title {
            color: var(--primary-blue);
            font-weight: 600;
            margin-bottom: 2rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--secondary-blue);
            border-radius: 2px;
        }

</style>
<body>
    <?php include '../../includes/navbar.php' ?>

    <!-- Knowledge Articles -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5">กิจกรรม</h2>
            <div class="row g-4">

                <?php if (!empty($activity_list)) {
                            $i = 1;
                            foreach ($activity_list as $item) {
                                $id = $item['activity_id'];
                                $title = $item['title'];
                                $string_image = $item['image'];
                                $first_image = explode(",", $item['image'])[0];
                                $content = strip_tags(mb_substr($item['content'], 0, 200, 'UTF-8') . '...');
                                $views_count = isset($item['views_count']) ? $item['views_count'] : 0;
                                $created_by = $item['created_by'];
                                $created_at = date('d-m-Y', strtotime($item['created_at']));
                ?>

                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-img-top" style="height: 250px;">
                            <img src="../../assets/images/activity/<?php echo $first_image ?>" alt="" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $title ?></h5>
                            <p class="card-text"><?php echo $content ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">เข้าชม <?php echo $views_count ?> ครั้ง</small>
                                <a href="detail.php?id=<?php echo $id ?>" class="btn btn-sm btn-primary">อ่านต่อ</a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php }} ?>

                
                
            </div>
            <!-- Pagination -->
        <div class="pagination-wrapper">
            <div class="row align-items-center">
                <?php

                echo $pagination->renderBootstrap();

                ?>
            </div>
        </div>

    </section>

    <?php include '../../includes/footer.php' ?>

    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>