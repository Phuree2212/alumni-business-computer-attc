<?php 
require_once '../auth/auth_admin.php';
require_once '../classes/admin.php';

$db = new Database();
$conn = $db->connect();
$admin = new Admin($conn, 'admin');

?>
<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/bootstrap-icons.min.css" rel="stylesheet">
  <link href="../assets/css/style_admin.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--light-bg);
    }

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

    .action-btn {
      width: 32px;
      height: 32px;
      border-radius: 6px;
      border: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin: 0 2px;
      transition: all 0.3s ease;
    }

    .action-btn:hover {
      transform: scale(1.1);
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

  <?php include 'includes/sidebar.php' ?>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Dashboard Stats -->
    <h2 class="section-title">สถิติเว็บไซต์</h2>
    <div class="row mb-5">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card primary">
          <div class="icon">
            <i class="fas fa-users"></i>
          </div>
          <h3>254,487</h3>
          <div class="label">ผู้เยี่ยมชมวันนี้</div>
          <div class="trend up">
            <i class="fas fa-arrow-up"></i>
            <span>7% เพิ่มขึ้นจากเมื่อวาน</span>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card success">
          <div class="icon">
            <i class="fas fa-comments"></i>
          </div>
          <h3>873</h3>
          <div class="label">ความคิดเห็น</div>
          <div class="trend up">
            <i class="fas fa-exclamation-circle"></i>
            <span>154 รอการอนุมัติ</span>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card warning">
          <div class="icon">
            <i class="fas fa-shopping-cart"></i>
          </div>
          <h3>2,423</h3>
          <div class="label">คำสั่งซื้อใหม่</div>
          <div class="trend up">
            <i class="fas fa-shopping-bag"></i>
            <span>954 ขายในเดือนนี้</span>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card danger">
          <div class="icon">
            <i class="fas fa-dollar-sign"></i>
          </div>
          <h3>$7,428</h3>
          <div class="label">รายได้</div>
          <div class="trend up">
            <i class="fas fa-chart-line"></i>
            <span>$22,675 รายได้รวม</span>
          </div>
        </div>
      </div>
    </div>

    <!-- User Table -->
    <div class="table-container">
      <h4 class="table-header">สมาชิกล่าสุด</h4>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>ผู้ใช้</th>
              <th>วันที่สมัคร</th>
              <th class="text-center">สถานะ</th>
              <th>อีเมล</th>
              <th class="text-center">จัดการ</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <img src="https://bootdey.com/img/Content/user_1.jpg" alt="User" class="user-avatar me-3">
                  <div>
                    <div class="fw-bold">สมชาย ใจดี</div>
                    <small class="text-muted">สมาชิก</small>
                  </div>
                </div>
              </td>
              <td>12/08/2024</td>
              <td class="text-center">
                <span class="badge bg-warning">รอการอนุมัติ</span>
              </td>
              <td>somchai@example.com</td>
              <td class="text-center">
                <button class="action-btn btn-outline-info">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="action-btn btn-outline-primary">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn btn-outline-danger">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <img src="https://bootdey.com/img/Content/user_3.jpg" alt="User" class="user-avatar me-3">
                  <div>
                    <div class="fw-bold">สมหญิง รักงาน</div>
                    <small class="text-muted">ผู้ดูแลระบบ</small>
                  </div>
                </div>
              </td>
              <td>10/08/2024</td>
              <td class="text-center">
                <span class="badge bg-success">ใช้งานอยู่</span>
              </td>
              <td>somying@example.com</td>
              <td class="text-center">
                <button class="action-btn btn-outline-info">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="action-btn btn-outline-primary">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn btn-outline-danger">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <img src="https://bootdey.com/img/Content/user_2.jpg" alt="User" class="user-avatar me-3">
                  <div>
                    <div class="fw-bold">สมศักดิ์ มีสุข</div>
                    <small class="text-muted">สมาชิก</small>
                  </div>
                </div>
              </td>
              <td>08/08/2024</td>
              <td class="text-center">
                <span class="badge bg-danger">ไม่ใช้งาน</span>
              </td>
              <td>somsak@example.com</td>
              <td class="text-center">
                <button class="action-btn btn-outline-info">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="action-btn btn-outline-primary">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn btn-outline-danger">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="../assets/js/bootstrap.bundle.js"></script>
  <script src="../assets/js/script_admin.js"></script>
</body>

</html>