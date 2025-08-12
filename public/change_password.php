<?php
require_once '../auth/auth_user.php';
require_once '../classes/user.php';
require_once '../classes/image_uploader.php';

$db = new Database();
$conn = $db->connect();

$user = new User($conn);

//api change password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $old_password = $_POST['old_password'];
  $new_password = $_POST['new_password'];

  echo json_encode($user->changePassword($_SESSION['user']['id'], $old_password, $new_password));
  exit;
}

?>

<!DOCTYPE html>
<html lang="th">

<head>
  <?php include '../includes/title.php' ?>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/bootstrap-icons.min.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <link href="../assets/css/sweetalert2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
</head>

<style>
  .alumni-detail-container {
    min-height: calc(100vh - 3.5rem);
    background-color: #f8f9fa;
  }

  .profile-header {
    background: linear-gradient(135deg, #467bcb 0%, #5a8dd8 100%);
    border-radius: 1rem;
    padding: 2rem;
    color: white;
    margin-bottom: 2rem;
  }

  .profile-avatar {
    width: 150px;
    height: auto;
    object-fit: cover;
    border: 5px solid #fff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
  }

  .detail-card {
    background-color: #fff;
    border-radius: 0.75rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    border: 1px solid #e9ecef;
    margin-bottom: 1.5rem;
    overflow: hidden;
    padding-left: 0;
    padding-right: 0;
  }

  .detail-card-header {
    width: 100%;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    padding: 1rem 1.5rem;
    font-weight: 600;
    color: #495057;
  }

  .detail-card-body {
    padding: 1.5rem;
  }

  .status-badge {
    background: linear-gradient(135deg, #467bcb 0%, #5a8dd8 100%);
    color: white;
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    border-radius: 1.5rem;
    font-weight: 500;
    display: inline-block;
  }

  .contact-links {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .contact-link {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
    text-decoration: none;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    border: 1px solid #dee2e6;
    background: #fff;
    color: #495057;
  }

  .contact-link:hover {
    background: #467bcb;
    color: white;
    border-color: #467bcb;
    transform: translateY(-2px);
    text-decoration: none;
  }

  .contact-link i {
    margin-right: 0.5rem;
    width: 16px;
  }

  .skill-badge {
    background: rgba(70, 123, 203, 0.1);
    color: #467bcb;
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-weight: 500;
    margin: 0.25rem;
    display: inline-block;
  }

  .project-card {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid #e9ecef;
  }

  .project-title {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
  }

  .project-tech {
    font-size: 0.75rem;
    color: #6c757d;
    background: rgba(108, 117, 125, 0.1);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    display: inline-block;
    margin-top: 0.5rem;
  }

  .certificate-item {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    margin-bottom: 0.5rem;
    border-left: 4px solid #467bcb;
  }

  .info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f3f4;
  }

  .info-row:last-child {
    border-bottom: none;
  }

  .info-label {
    font-weight: 600;
    color: #495057;
    min-width: 120px;
  }

  .info-value {
    color: #6c757d;
    flex: 1;
    text-align: right;
  }

  .breadcrumb-custom {
    background: #fff;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  }

  .btn-back {
    background: linear-gradient(135deg, #6c757d 0%, #868e96 100%);
    border: none;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .btn-back:hover {
    background: linear-gradient(135deg, #495057 0%, #6c757d 100%);
    color: white;
    transform: translateY(-1px);
  }

  .gpa-circle {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #467bcb 0%, #5a8dd8 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.25rem;
    margin: 0 auto;
  }

  @media (max-width: 767.98px) {
    .profile-header {
      padding: 1.5rem;
      text-align: center;
    }

    .profile-avatar {
      width: 120px;
      height: auto;
      margin: 0 auto 1rem auto;
    }

    .contact-links {
      justify-content: center;
    }

    .info-row {
      flex-direction: column;
      align-items: flex-start;
      text-align: left;
    }

    .info-value {
      text-align: left;
      margin-top: 0.25rem;
    }

    .detail-card-body {
      padding: 1rem;
    }
  }
</style>

<body>
  <?php include '../includes/navbar.php' ?>

  <div class="container-xxl py-3">

    <div class="alumni-detail-container m-2">

      <!-- ฟอร์มแก้ไขข้อมูล -->
      <div class="row">
        <form id="formEditData" action="profile.php" method="post">
          <!-- Personal Information -->
          <div class="detail-card" id="showMode">
            <div class="detail-card-header">
              <i class="fa fa-key me-2"></i>แก้ไขรหัสผ่าน
            </div>
            <div class="detail-card-body">
              <div class="info-row">
                <span class="info-label">รหัสผ่านเดิม</span>
                <div class="w-100">
                  <input name="old_password" id="oldPassword" type="password" class="form-control" placeholder=""></input>
                  <div class="invalid-feedback"></div>
                </div>
              </div>
              <div class="info-row">
                <span class="info-label">รหัสผ่านใหม่</span>
                <div class="w-100">
                  <input name="new_password" id="newPassword" type="password" class="form-control" placeholder=""></input>
                  <div class="invalid-feedback"></div>
                </div>
              </div>
              <div class="info-row">
                <span class="info-label">ยืนยันรหัสผ่านใหม่</span>
                <div class="w-100">
                  <input name="new_password_confirm" id="newPasswordConfirm" type="password" class="form-control" placeholder=""></input>
                  <div class="invalid-feedback"></div>
                </div>
              </div>
              <div class="text-end">
                <button type="button" onclick="history.back()" class="btn btn-secondary">ยกเลิก</button>
                <button type="button" id="btnSubmit" class="btn btn-success">ยืนยันการเปลี่ยนรหัสผ่าน</button>
              </div>
            </div>
          </div>
        </form>
      </div>

    </div>

  </div>

  <?php include '../includes/footer.php' ?>

  <script src="../assets/js/bootstrap.min.js"></script>
  <script src="../assets/js/sweetalert2.all.min.js"></script>
  <script src="../assets/alerts/modal.js"></script>
  <script src="function/validate_form.js"></script>
  <script>
    const btnSubmit = document.getElementById('btnSubmit');
    const formEditData = document.getElementById('formEditData');

    btnSubmit.addEventListener('click', (e) => {
      e.preventDefault();

      const formData = new FormData(formEditData);
      clearValidation('formEditData');

      if (validateForm()) {
        modalConfirm('ยืนยันการเปลี่ยนรหัสผ่าน', 'คุณต้องการเปลี่ยนรหัสผ่านใช่หรือไม่')
          .then(result => {
            if (result.isConfirmed) {
              fetch('change_password.php', {
                  method: 'POST',
                  body: formData
                })
                .then(response => response.json())
                .then(response => {
                  if (response.result) {
                    modalAlert('เปลี่ยนรหัสผ่านสำเร็จ', 'รหัสผ่านของท่านได้ถูกเปลี่ยนเรียบร้อยแล้ว', 'success')
                      .then(() => {
                        window.location.href = 'profile.php'
                      })
                  } else {
                    modalAlert('เปลี่ยนรหัสผ่านไม่สำเร็จ', response.message, 'error');
                  }
                })
                .catch(error => {
                  console.error('Fetch Error:', error);
                  modalAlert('เกิดข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้', 'error');
                });
            }
          })
      }

    });

    //ตรวจสอบความถูกต้องของฟอร์ม
    function validateForm() {
      const formData = new FormData(formEditData);
      const data = Object.fromEntries(formData);

      let isValid = true;

      if (!validatePassword(data.old_password)) {
        showFieldError('oldPassword', 'รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษรขึ้นไป');
        isValid = false;
      }

      if (!validatePassword(data.new_password)) {
        showFieldError('newPassword', 'รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษรขึ้นไป');
        isValid = false;
      }

      if (data.new_password != data.new_password_confirm) {
        showFieldError('newPasswordConfirm', 'รหัสผ่านไม่ตรงกัน');
        isValid = false;
      }

      return isValid;
    }
  </script>
</body>

</html>