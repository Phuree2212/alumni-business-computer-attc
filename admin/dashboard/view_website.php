<?php
require_once '../../auth/auth_admin.php';
require_once '../../classes/user.php';
require_once '../../classes/visitor_tracker.php';
require_once '../../classes/webboard.php';
require_once '../../classes/dashboard.php';

$db = new Database();
$conn = $db->connect();

$visitor_tracker = new VisitorTracker($conn);
$view_count = $visitor_tracker->countViewWebsite();

$dashboard = new Dashboard($conn);

if ($_SERVER['REQUEST_METHOD'] == "GET" && (isset($_GET['group']) || isset($_GET['start']) || isset($_GET['end']))) {
    $group = $_GET['group'] ?? 'day';   // กำหนด default กรณีไม่มี group
    $start = $_GET['start'] ?? null;
    $end = $_GET['end'] ?? null;
    $dashboard_view_website = $dashboard->getViewWebsite($group, $start, $end);
} else {
    $dashboard_view_website = $dashboard->getViewWebsite();
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
        <h2 class="section-title">Dashboard สถิติการเข้าชมเว็บไซต์</h2>
        <div class="row mb-5">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card primary">
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3><?php echo $view_count ?> ครั้ง</h3>
                    <div class="label">ผู้เยี่ยมชมเว็บไซต์</div>
                </div>
            </div>


            <!-- กราฟแสดงสถิติการเข้าชมเว็บไซต์ -->
            <div>
                <h3>กราฟแสดงสถิติการเข้าชมเว็บไซต์</h3>
                <!-- Filter Section -->
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body">
                        <form action="view_website.php" method="get">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-2">
                                    <label for="group" class="form-label fw-semibold text-secondary">แสดงข้อมูลแบบ:</label>
                                    <select name="group" id="group" class="form-select">
                                        <option value="day" <?= ($_GET['group'] ?? '') === 'day' ? 'selected' : '' ?>>รายวัน</option>
                                        <option value="month" <?= ($_GET['group'] ?? '') === 'month' ? 'selected' : '' ?>>รายเดือน</option>
                                        <option value="year" <?= ($_GET['group'] ?? '') === 'year' ? 'selected' : '' ?>>รายปี</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="start" class="form-label fw-semibold text-secondary">
                                        ตั้งแต่:
                                    </label>
                                    <input type="date" name="start" id="start" value="<?= $_GET['start'] ?? '' ?>" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label for="end" class="form-label fw-semibold text-secondary">
                                        ถึง:
                                    </label>
                                    <input type="date" name="end" id="end" value="<?= $_GET['end'] ?? '' ?>" class="form-control">
                                </div>

                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">
                                        แสดงผล
                                    </button>
                                    <a href="view_website.php" class="btn btn-outline-secondary">
                                        รีเซ็ต
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <canvas id="viewWebsiteChart"></canvas>

                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>วัน/เดือน/ปี</th>
                                <th class="text-center">จำนวน</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php if (!empty($dashboard_view_website)) {
                                foreach ($dashboard_view_website as $at => $count) {
                            ?>
                                    <tr>
                                        <td><?= date('d-m-Y',strtotime($at)) ?></td>
                                        <td class="text-center"><?= $count ?> ครั้ง</td>
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
        <script>
            function viewWebsiteChart() {
                const ctx = document.getElementById('viewWebsiteChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode(array_keys($dashboard_view_website)); ?>,
                        datasets: [{
                            label: 'จำนวน',
                            data: <?php echo json_encode(array_values($dashboard_view_website)); ?>,
                            backgroundColor: ['#4e73df'], // สีน้ำเงิน/เหลือง
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            viewWebsiteChart();
        </script>
</body>

</html>