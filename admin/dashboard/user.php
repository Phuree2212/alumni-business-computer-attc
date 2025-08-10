<?php
require_once '../../auth/auth_admin.php';
require_once '../../classes/user.php';
require_once '../../classes/visitor_tracker.php';
require_once '../../classes/webboard.php';
require_once '../../classes/dashboard.php';

$db = new Database();
$conn = $db->connect();

$user = new User($conn);
$user_count = $user->countUser();

$dashboard = new Dashboard($conn);
$dashboard_user_type = $dashboard->getUserType();

$education_level = $_GET['education_level'] ?? '';
$graduation_year = $_GET['graduation_year'] ?? '';
$status_education = $_GET['status_education'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == "GET" && (!empty($graduation_year) || !empty($education_level || !empty($status_education)))) {
    $dashboard_alumni_year = $dashboard->getAlumniYear($education_level, $graduation_year, $status_education);
    $dashboard_education_level = $dashboard->getAlumniEducationLevel($education_level, $graduation_year, $status_education);
    $dashboard_status_education = $dashboard->getAlumniStatusEducation($education_level, $graduation_year, $status_education);
} else {
    $dashboard_alumni_year = $dashboard->getAlumniYear();
    $dashboard_education_level = $dashboard->getAlumniEducationLevel();
    $dashboard_status_education = $dashboard->getAlumniStatusEducation();
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
        <h2 class="section-title">Dashboard สถิติสมาชิกในเว็บไซต์</h2>
        <div class="row mb-5">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card warning">
                    <div class="icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3><?php echo $user_count ?> คน</h3>
                    <div class="label">สมาชิกในเว็บไซต์</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card primary">
                    <div class="icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3><?php echo $dashboard_user_type['alumni'] ?> คน</h3>
                    <div class="label">ศิษย์เก่า</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card success">
                    <div class="icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3><?php echo $dashboard_user_type['student'] ?> คน</h3>
                    <div class="label">นักเรียน นักศึกษา</div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <canvas id="userTypeChart"></canvas>
            </div>

        </div>


        <!-- กราฟแสดงสถิติการเข้าชมเว็บไซต์ -->
        <div>
            <h3>กราฟแสดงสถิติสมาชิกศิษย์เก่า</h3>
            <!-- Filter Section -->
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body">
                    <form action="user.php" method="get">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-2">
                                <label for="user_type" class="form-label fw-semibold text-secondary">ประเภทผู้ใช้งาน:</label>
                                <select name="user_type" id="user_type" class="form-select">
                                    <option value="alumni" <?= ($_GET['user_type'] ?? '') === 'alumni' ? 'selected' : '' ?>>ศิษย์เก่า</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="start" class="form-label fw-semibold text-secondary">
                                    ปีที่จบการศึกษา:
                                </label>
                                <select name="graduation_year" class="form-select">
                                    <option selected value="">ทั้งหมด</option>
                                    <?php $year = 2540; ?>
                                    <?php for ($i = $year; $i <= date('Y') + 543; $i++) { ?>
                                        <option <?php echo $graduation_year == $i ? 'selected' : '' ?> value="<?php echo $i ?>"><?php echo $i ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="end" class="form-label fw-semibold text-secondary">
                                    ระดับชั้นที่จบการศึกษา:
                                </label>
                                <select class="form-select" name="education_level" id="searchLevel">
                                    <option selected value="">ทั้งหมด</option>
                                    <option <?php echo $education_level == 'ปวช.3' ? 'selected' : '' ?> value="ปวช.3">ปวช.3</option>
                                    <option <?php echo $education_level == 'ปวส.2' ? 'selected' : '' ?> value="ปวส.2">ปวส.2</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="end" class="form-label fw-semibold text-secondary">
                                    สถ่านะการศึกษาต่อ:
                                </label>
                                <select class="form-select" name="status_education" id="searchLevel">
                                    <option selected value="">ทั้งหมด</option>
                                    <option <?php echo $status_education == 'ทำงานแล้ว' ? 'selected' : '' ?> value="ทำงานแล้ว">ทำงานแล้ว</option>
                                    <option <?php echo $status_education == 'ศึกษาค่อ' ? 'selected' : '' ?> value="ศึกษาค่อ">ศึกษาต่อ</option>
                                    <option <?php echo $status_education == 'ว่างงาน' ? 'selected' : '' ?> value="ว่างงาน">ว่างงาน</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">
                                    แสดงผล
                                </button>
                                <a href="user.php" class="btn btn-outline-secondary">
                                    รีเซ็ต
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-xl-3 col-md-6 mb-2">
                    <h4>กราฟแสดงจำนวนปีที่จบการศึกษา</h4>
                    <canvas class="mb-2" id="chartYear"></canvas>

                    <h4>ตารางแสดงจำนวนปีที่จบการศึกษา</h4>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ปีที่จบการศึกษา</th>
                                    <th class="text-center">จำนวน</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if (!empty($dashboard_alumni_year)) {
                                    foreach ($dashboard_alumni_year as $year => $count) {
                                ?>
                                        <tr>
                                            <td><?= $year ?></td>
                                            <td class="text-center"><?= $count ?> คน</td>
                                        </tr>
                                <?php }
                                } ?>


                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-2">
                    <h4>กราฟแสดงระดับชั้นที่จบการศึกษา</h4>
                    <canvas class="mb-2" id="chartEducationLevel"></canvas>

                    <h4>ตารางแสดงระดับชั้นที่จบการศึกษา</h4>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ระดับชั้นที่จบการศึกษา</th>
                                    <th class="text-center">จำนวน</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if (!empty($dashboard_education_level)) {
                                    foreach ($dashboard_education_level as $level => $count) {
                                ?>
                                        <tr>
                                            <td><?= $level ?></td>
                                            <td class="text-center"><?= $count ?> คน</td>
                                        </tr>
                                <?php }
                                } ?>


                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-2">
                    <h4>กราฟแสดงสถานะการศึกษา / ทำงาน</h4>
                    <canvas class="mb-2" id="chartStatusEducation"></canvas>

                    <h4>ตารางแสดงสถานะการศึกษา / ทำงาน</h4>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ระดับชั้นที่จบการศึกษา</th>
                                    <th class="text-center">จำนวน</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if (!empty($dashboard_status_education)) {
                                    foreach ($dashboard_status_education as $status => $count) {
                                ?>
                                        <tr>
                                            <td><?= $status ?></td>
                                            <td class="text-center"><?= $count ?> คน</td>
                                        </tr>
                                <?php }
                                } ?>


                            </tbody>
                        </table>
                    </div>
                </div>

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
        function chartYear() {
            const ctx = document.getElementById('chartYear').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_keys($dashboard_alumni_year)); ?>,
                    datasets: [{
                        label: 'จำนวน',
                        data: <?php echo json_encode(array_values($dashboard_alumni_year)); ?>,
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

        chartYear();

        function chartEducationLevel() {
            const ctx = document.getElementById('chartEducationLevel').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode(array_keys($dashboard_education_level)); ?>,
                    datasets: [{
                        label: 'จำนวน',
                        data: <?php echo json_encode(array_values($dashboard_education_level)); ?>,
                        backgroundColor: ['#4e73df', '#f6c23e'], // สีน้ำเงิน/เหลือง
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

        chartEducationLevel();

        function chartStatusEducation() {
            const ctx = document.getElementById('chartStatusEducation').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_keys($dashboard_status_education)); ?>,
                    datasets: [{
                        label: 'จำนวน',
                        data: <?php echo json_encode(array_values($dashboard_status_education)); ?>,
                        backgroundColor: ['#424242ff', '#f6c23e', '#d8ffbeff', '#5f7cffff'], // สีน้ำเงิน/เหลือง
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

        chartStatusEducation();

        function dashboardUserType() {
            const ctx = document.getElementById('userTypeChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode(array_keys($dashboard_user_type)); ?>,
                    datasets: [{
                        label: 'จำนวน',
                        data: <?php echo json_encode(array_values($dashboard_user_type)); ?>,
                        backgroundColor: ['#4e73df', '#f6c23e'], // สีน้ำเงิน/เหลือง
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

        dashboardUserType();
    </script>
</body>

</html>