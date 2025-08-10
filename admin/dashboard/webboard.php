<?php
require_once '../../auth/auth_admin.php';
require_once '../../classes/user.php';
require_once '../../classes/visitor_tracker.php';
require_once '../../classes/webboard.php';
require_once '../../classes/dashboard.php';

$db = new Database();
$conn = $db->connect();

$webboard = new Webboard($conn);
$count_forum = $webboard->getTotalCount();

$comment = new CommentForum($conn);
$count_comment = $comment->getTotalCountComment();

$dashboard = new Dashboard($conn);

$group_type = $_GET['group_type'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == "GET" && (!empty($group_type))) {
    $dashboard_forum = $dashboard->getForumTopTen($group_type);
} else {
    $dashboard_forum = $dashboard->getForumTopTen();
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">>
    <style>
        /* Dashboard Cards */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .header {
            color: var(--primary-color);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stats-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 20px;
        }

        .stats-card.primary .icon {
            background: var(--accent-color);
        }

        .stats-card.success .icon {
            background: var(--success-color);
        }

        .stats-card.danger .icon {
            background: var(--danger-color);
        }

        .stats-card.warning .icon {
            background: var(--warning-color);
        }

        .stats-card h3 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        .stats-card .label {
            color: #6c757d;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .stats-card .trend {
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .trend.up {
            color: var(--success-color);
        }

        .trend.down {
            color: var(--danger-color);
        }

        /* User Table */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-header {
            background: var(--primary-color);
            color: white;
            padding: 20px 25px;
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .table {
            margin: 0;
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: var(--primary-color);
            border: none;
            padding: 15px;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
            border-color: #f1f3f4;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .badge {
            font-size: 11px;
            font-weight: 600;
            padding: 6px 12px;
        }

        .section-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 10px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 20px 15px;
            }
        }

        /* Toggle Button for Mobile */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: var(--primary-color);
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block;
            }
        }
    </style>
</head>

<body>

    <?php include '../includes/sidebar.php' ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Dashboard Stats -->
        <h2 class="section-title">Dashboard สถิติเว็บบอร์ด</h2>
        <div class="row mb-5">

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card success">
                    <div class="icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3><?php echo $count_forum ?></h3>
                    <div class="label">กระทู้</div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card success">
                    <div class="icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3><?php echo $count_comment ?></h3>
                    <div class="label">ความคิดเห็น</div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <canvas id="userTypeChart"></canvas>
            </div>

        </div>


        <!-- กราฟแสดงสถิติการเข้าชมเว็บไซต์ -->
        <div>
            <h3>แสดงสถิติกระทู้</h3>
            <!-- Filter Section -->
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body">
                    <form action="webboard.php" method="get">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-2">
                                <label for="group_type" class="form-label fw-semibold text-secondary">ประเภทกระทู้:</label>
                                <select name="group_type" id="group_type" class="form-select">
                                    <option value="" selected>ทั้งหมด</option>
                                    <option value="public" <?= ($_GET['group_type'] ?? '') === 'public' ? 'selected' : '' ?>>สาธารณะ</option>
                                    <option value="year_group" <?= ($_GET['group_type'] ?? '') === 'year_group' ? 'selected' : '' ?>>ตามปีการศึกษา</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    แสดงผล
                                </button>
                                <a href="webboard.php" class="btn btn-outline-secondary">
                                    รีเซ็ต
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <h4>10 อันดับกระทู้ที่ได้รับความนิยมสูงสุด</h4>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>วัน/เดือน/ปี ที่โพสต์</th>
                            <th class="text-center">ผู้โพสต์</th>
                            <th>หัวเรื่อง</th>
                            <th class="text-center">จำนวนการกดถูกใจ</th>
                            <th class="text-center">จำนวนความคิดเห็น</th>
                            <th class="text-center">ดูโพสต์</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (!empty($dashboard_forum)) {
                            foreach ($dashboard_forum as $item) {
                        ?>
                                <tr>
                                    <td><?= $item['created_at'] ?></td>
                                    <td class="text-center"><?= $item['first_name'] . ' ' . $item['last_name'] ?></td>
                                    <td><?= $item['title'] ?></td>
                                    <td class="text-center"><?= $item['count_like'] ?> ครั้ง</td>
                                    <td class="text-center"><?= $item['count_comment'] ?> ครั้ง</td>
                                    <td class="text-center"><a href="#">ดูโพสต์</a></td>
                                </tr>
                        <?php }
                        } ?>


                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="../../assets/js/script_admin.js"></script>
    <script src="../../assets/js/sweetalert2.all.min.js"></script>
    <script src="../../assets/alerts/modal.js"></script>
</body>

</html>