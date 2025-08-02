<?php
require_once '../auth/auth_admin.php';
require_once '../classes/user.php';
require_once '../classes/visitor_tracker.php';
require_once '../classes/webboard.php';

$db = new Database();
$conn = $db->connect();

$visitor_tracker = new VisitorTracker($conn);
$view_count = $visitor_tracker->countViewWebsite(); 

$user = new User($conn);
$user_count = $user->countUser();

$webboard = new Webboard($conn);
$count_forum = $webboard->getTotalCount();

$comment = new CommentForum($conn);
$count_comment = $comment->getTotalCountComment();

$user_approval = new UserApproval($conn);
$user_approval_list = $user_approval->getRegisterWaiingApprove(10, 0);


?>
<!DOCTYPE html>
<html lang="th">

<head>
  <?php include '../includes/title.php'; ?>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/bootstrap-icons.min.css" rel="stylesheet">
  <link href="../assets/css/style_admin.css" rel="stylesheet">
  <link href="../assets/css/sweetalert2.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

  <?php include 'includes/sidebar.php' ?>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Dashboard Stats -->
    <h2 class="section-title">หน้าหลัก</h2>
    <div class="header text-center mb-4">
      <h2>ยินดีต้อนรับผู้ดูแลระบบ</h2>
      <h2>ระบบจัดการข้อมูล เว็บไซต์สายใยคอมพิวเตอร์ธุรกิจ วิทยาลัยเทคนิคอ่างทอง</h2>
    </div>
    <div class="row mb-5">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card primary">
          <div class="icon">
            <i class="fas fa-users"></i>
          </div>
          <h3><?php echo $view_count ?></h3>
          <div class="label">ผู้เยี่ยมชมเว็บไซต์</div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card warning">
          <div class="icon">
            <i class="fas fa-user"></i>
          </div>
          <h3><?php echo $user_count ?></h3>
          <div class="label">สมาชิกในเว็บไซต์</div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card success">
          <div class="icon">
            <i class="fas fa-user"></i>
          </div>
          <h3><?php echo $count_forum ?></h3>
          <div class="label">กระทู้</div>
            <!--
            <div class="trend up">
              <i class="fas fa-exclamation-circle"></i>
              <span>154 รอการอนุมัติ</span>
            </div>
            -->
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

    </div>

    <!-- User Table -->
    <div class="table-container">
      <h4 class="table-header">คำขอลงทะเบียนล่าสุด</h4>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>ลำดับที่</th>
              <th>รหัสนักศึกษา</th>
              <th>ชื่อ-นามสกุล</th>
              <th>อิเมลล์</th>
              <th>เบอร์โทรศัพท์</th>
              <th>ประเภทผู้ใช้</th>
              <th>ระดับชั้นที่ศึกษาปัจจุบัน<br> / ระดับชั้นที่จบการศึกษา</th>
              <th>ปีที่จบการศึกษา</th>
              <th>สถานะ</th>
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
                  <td class="text-center"><?php echo $education_level; ?></td>
                  <td class="text-center"><?php echo $graduation_year; ?></td>
                  <td><?php echo $status_register; ?></td>
                  <td><?php echo $created_at; ?></td>
                  <td class="text-center">
                    <button class="btn btn-success" onclick="approveUser(<?php echo $id ?>)">อนุมัติ</button>
                    <button class="btn btn-danger" onclick="rejectUser(<?php echo $id ?>)">ไม่อนุมัติ</button>
                  </td>
                </tr>
            <?php }
            } ?>
    <!--
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
            -->
          </tbody>
        </table>

        <?php if (empty($user_approval_list)) { ?>
            <div class="text-center text-danger p-5">ไม่พบข้อมูลผู้ลงทะเบียนใหม่</div>
        <?php } ?>

      </div>
    </div>
  </div>

  <script src="../assets/js/bootstrap.bundle.js"></script>
  <script src="../assets/js/script_admin.js"></script>
  <script src="../assets/js/sweetalert2.all.min.js"></script>
  <script src="../assets/alerts/modal.js"></script>
</body>
<script>
        function approveUser(userId) {
            return modalConfirm('อนุมัติการลงทะเบียนผู้ใช้', `ยืนยันการอนุมัติลงทะเบียนบัญชีผู้ใช้ที่ ID ${userId} ใช่ไหม?`)
                .then((result) => {
                    if (result.isConfirmed) {
                        fetch('user/user_approval/approve.php', {
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
                        fetch('user/user_approval/reject.php', {
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

</html>