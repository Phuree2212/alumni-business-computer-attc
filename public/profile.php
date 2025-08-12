<?php
require_once '../auth/auth_user.php';
require_once '../classes/image_uploader.php';

$db = new Database();
$conn = $db->connect();

//ตรวจสอบประเภทผู้ใช้งานเพื่อดึงการใช้งาน Class
if ($_SESSION['user']['user_type'] == USER_TYPE_ALUMNI) {
  require_once '../classes/alumni.php';
  $alumni = new Alumni($conn);
  $detail = $alumni->getAlumni($_SESSION['user']['id']);
}
if ($_SESSION['user']['user_type'] == USER_TYPE_STUDENT) {
  require_once '../classes/student.php';
  $student = new Student($conn);
  $detail = $student->getStudent($_SESSION['user']['id']);
}

//api edit data user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  $student_code = $_POST['student_code'];
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $status_register = 1;
  $education_level = $_POST['education_level'];
  $graduation_year = $_POST['graduation_year'];

  $status_education = !empty($_POST['status_education']) ? $_POST['status_education'] : '';

  $current_job = !empty($_POST['current_job']) ? $_POST['current_job'] : '';
  $current_company = !empty($_POST['current_company']) ? $_POST['current_company'] : '';
  $current_salary = !empty($_POST['current_salary']) ? $_POST['current_salary'] : '';

  $address = $_POST['address'];
  $facebook = $_POST['facebook'];
  $instagram = $_POST['instagram'];
  $tiktok = $_POST['tiktok'];
  $line = $_POST['line'];
  /*
  $status_education = $_POST['status_education'];
  
  $current_job = $_POST['current_job'];
  $current_company = $_POST['current_company'];
  $current_salary = $_POST['current_salary'];
  */

  $image = $_FILES['image'];
  $current_images = $_POST['current_images'];
  $deleted_images = $_POST['deleted_images'];

  $user_folder = ($_SESSION['user']['user_type'] == USER_TYPE_ALUMNI) ? 'alumni' : 'student';
  $path = '../assets/images/user/' . $user_folder;


  if (!empty($deleted_images)) {
    // Delete physical files using ImageUploader's deleteFile method
    $uploader = new ImageUploader($path);
    $uploader->deleteFile($deleted_images);
  }

  $new_image_files = '';
  if (!empty($image['name'])) {
    $uploader = new ImageUploader($path);
    $uploader->setMaxFileSize(5 * 1024 * 1024) // MAX SIZE 5MB
      ->setMaxFiles(1); // Limit based on existing images

    $new_image_files .= $student_code . '_' . $first_name;
    $result = $uploader->uploadSingle($_FILES['image'], $new_image_files);
    $new_image_files = $result['fileName'];
  } else {
    //ถ้าไม่มีการอัพโหลดรูปภาพใหม่ ให้ใช้ภาพเดิม
    $new_image_files = $current_images;
  }

  $_SESSION['user']['image'] = $new_image_files;

  if ($_SESSION['user']['user_type'] == USER_TYPE_STUDENT)
    $result = $student->editStudent(
      $id,
      $student_code,
      $first_name,
      $last_name,
      $email,
      $phone,
      $education_level,
      $status_register,
      $new_image_files,
      $address,
      $facebook,
      $instagram,
      $line,
      $tiktok
    );

  if ($_SESSION['user']['user_type'] == USER_TYPE_ALUMNI)
    $result = $alumni->editAlumni(
      $id,
      $student_code,
      $first_name,
      $last_name,
      $email,
      $phone,
      $education_level,
      $graduation_year,
      $status_register,
      $current_job,
      $current_company,
      $current_salary,
      $new_image_files,
      $status_education,
      $address,
      $facebook,
      $instagram,
      $line,
      $tiktok
    );

  echo json_encode($result);
  exit;
}

//สร้างตัวแปรรับค่าข้อมูลแสดงในหน้าเว็บ
$id = $_SESSION['user']['id'];
$user_type = $_SESSION['user']['user_type'];
$fullname = $_SESSION['user']['fullname'];
$first_name = explode(' ', $fullname)[0];
$last_name = explode(' ', $fullname)[1];
$education_level = $_SESSION['user']['education_level'];
$graduation_year = $_SESSION['user']['graduation_year'];
$image =  $_SESSION['user']['image'];

$student_code = $detail['student_code'];
$address = !empty($detail['address']) ? $detail['address'] : "";
$password = $detail['password'];

$status_education = $detail['status_education'];

$current_job = $detail['current_job'];
$current_company = $detail['current_company'];
$current_salary = $detail['current_salary'];

$email = $detail['email'];
$phone = $detail['phone'];
$facebook = !empty($detail['facebook']) ? $detail['facebook'] : "";;
$instagram = !empty($detail['instagram']) ? $detail['instagram'] : "";;
$line = !empty($detail['line']) ? $detail['line'] : "";;
$tiktok = !empty($detail['tiktok']) ? $detail['tiktok'] : "";;

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
      <!-- Profile Header -->

      <div class="row" id="showMode">

        <!-- Personal Information -->
        <div class="detail-card" id="showMode">
          <div class="detail-card-header">
            <i class="fas fa-user me-2"></i>ข้อมูลส่วนตัว
          </div>
          <div class="detail-card-body">
            <div class="info-row">
              <span class="info-label">โปรไฟล์</span>
              <img src="<?php echo empty($image) ? '../assets/images/user/no-image-profile.jpg' : '../assets/images/user/' . ($user_type == USER_TYPE_ALUMNI ? 'alumni' : 'student') . '/' . $image ?>" class="profile-avatar" alt="">
            </div>
            <div class="info-row">
              <span class="info-label">ชื่อ-นามสกุล</span>
              <span id="editFullname" class="info-value"><?php echo $fullname ?></span>
            </div>
            <div class="info-row">
              <span class="info-label">รหัสนักศึกษา</span>
              <span class="info-value"><?php echo $student_code ?></span>
            </div>
            <div class="info-row">
              <span class="info-label">รหัสผ่าน</span>
              <span class="info-value">********</span>
            </div>
            <div class="info-row">
              <span class="info-label"><?php echo $_SESSION['user']['user_type'] == USER_TYPE_ALUMNI ? 'ระดับชั้นที่จบการศึกษา' : 'ระดับชั้น' ?></span>
              <span class="info-value"><?php echo $education_level ?></span>
            </div>
            <?php if ($_SESSION['user']['user_type'] == USER_TYPE_ALUMNI) { ?>
              <div class="info-row">
                <span class="info-label">ปีที่จบการศึกษา</span>
                <span class="info-value"><?php echo $graduation_year ?></span>
              </div>
            <?php } ?>
            <div class="info-row">
              <span class="info-label">ประเภทผู้ใช้งาน</span>
              <span class="info-value"><?php echo $user_type == USER_TYPE_ALUMNI ? 'ศิษย์เก่า' : 'นักเรียน นักศึกษา' ?></span>
            </div>
            <?php if ($_SESSION['user']['user_type'] == USER_TYPE_ALUMNI) { ?>
              <div class="info-row">
                <span class="info-label">สถานะการศึกษาต่อ</span>
                <span class="info-value <?php echo !empty($status_education) ? '' : 'text-danger' ?>"><?php echo !empty($status_education) ? $status_education : 'ยังไม่ได้กรอกข้อมูล' ?></span>
              </div>
            <?php } ?>
            <div class="info-row">
              <span class="info-label">ที่อยู่</span>
              <span class="info-value <?php echo !empty($address) ? '' : 'text-danger' ?>"><?php echo !empty($address) ? $address : 'ยังไม่ได้กรอกข้อมูล' ?></span>
            </div>
          </div>
        </div>

        <?php if ($_SESSION['user']['user_type'] == USER_TYPE_ALUMNI) { ?>
          <!-- Job Information -->
          <div class="detail-card">
            <div class="detail-card-header">
              <i class="fas fa-book me-2"></i>ข้อมูลการทำงาน
            </div>
            <div class="detail-card-body">
              <div class="info-row">
                <span class="info-label">ตำแหน่งงานปัจจุบัน</span>
                <span class="info-value <?php echo !empty($current_job) ? '' : 'text-danger' ?>"><?php echo !empty($current_job) ? $current_job : 'ยังไม่ได้กรอกข้อมูล' ?></span>
              </div>
              <div class="info-row">
                <span class="info-label">ชื่อสถานที่ทำงาน / บริษัท</span>
                <span class="info-value <?php echo !empty($current_company) ? '' : 'text-danger' ?>"><?php echo !empty($current_company) ? $current_company : 'ยังไม่ได้กรอกข้อมูล' ?></span>
              </div>

              <div class="info-row">
                <span class="info-label">เงินเดือน</span>
                <span class="info-value <?php echo !empty($current_salary) ? '' : 'text-danger' ?>"><?php echo !empty($current_salary) ? number_format($current_salary) : 'ยังไม่ได้กรอกข้อมูล' ?> บาท</span>
              </div>
            </div>
          </div>
        <?php } ?>

        <!-- Contact Information -->
        <div class="detail-card">
          <div class="detail-card-header">
            <i class="fas fa-book me-2"></i>ช่องทางการติดต่อ
          </div>
          <div class="detail-card-body">
            <div class="info-row">
              <span class="info-label">Email</span>
              <span class="info-value"><?php echo $email ?></span>
            </div>
            <div class="info-row">
              <span class="info-label">Telephone</span>
              <span class="info-value"><?php echo $phone ?></span>
            </div>

            <div class="info-row">
              <span class="info-label">Facebook Link</span>
              <span class="info-value">
                <?php if (!empty($facebook)): ?>
                  <a href="<?= $facebook ?>" target="_blank"><?= $facebook ?></a>
                <?php else: ?>
                  <span class="text-danger">ยังไม่ได้กรอกข้อมูล</span>
                <?php endif; ?>
              </span>
            </div>

            <div class="info-row">
              <span class="info-label">Instagram Link</span>
              <span class="info-value">
                <?php if (!empty($instagram)): ?>
                  <a href="<?= $instagram ?>" target="_blank"><?= $instagram ?></a>
                <?php else: ?>
                  <span class="text-danger">ยังไม่ได้กรอกข้อมูล</span>
                <?php endif; ?>
              </span>
            </div>

            <div class="info-row">
              <span class="info-label">Line ID</span>
              <span class="info-value">
                <?php if (!empty($line)): ?>
                  <a href="https://line.me/ti/p/~<?= $line ?>" target="_blank"><?= $line ?></a>
                <?php else: ?>
                  <span class="text-danger">ยังไม่ได้กรอกข้อมูล</span>
                <?php endif; ?>
              </span>
            </div>

            <div class="info-row">
              <span class="info-label">Tiktok Link</span>
              <span class="info-value">
                <?php if (!empty($tiktok)): ?>
                  <a href="<?= $tiktok ?>" target="_blank"><?= $tiktok ?></a>
                <?php else: ?>
                  <span class="text-danger">ยังไม่ได้กรอกข้อมูล</span>
                <?php endif; ?>
              </span>
            </div>
          </div>
        </div>

        <div class="text-end">
          <button onclick="history.back()" class="btn btn-secondary">กลับ</button>
          <button id="btnEdit" class="btn btn-warning">แก้ไขข้อมูล</button>
        </div>
      </div>




      <!-- ฟอร์มแก้ไขข้อมูล -->
      <div class="row" id="editMode" style="display: none;">
        <form id="formEditData" action="profile.php" method="post" enctype="multipart/form-data">
          <!-- Personal Information -->
          <div class="detail-card" id="showMode">
            <div class="detail-card-header">
              <i class="fas fa-user me-2"></i>ข้อมูลส่วนตัว
            </div>
            <div class="detail-card-body">
              <input type="hidden" name="id" id="id" value="<?php echo $id ?>">
              <input type="hidden" name="deleted_images" id="deletedImages" value="">
              <input type="hidden" name="current_images" id="currentImages" value="<?php echo $image ?>">

              <div class="info-row">
                <span class="info-label">โปรไฟล์</span>
                <div class="d-flex flex-column gap-2">
                  <img id="imagePreview" src="<?php echo empty($image) ? '../assets/images/user/no-image-profile.jpg' : '../assets/images/user/' . ($user_type == USER_TYPE_ALUMNI ? 'alumni' : 'student') . '/' . $image ?>" class="profile-avatar">
                  <label class="btn btn-primary">
                    เลือกรูปภาพ
                    <input type="file" name="image" id="editProfilePic" accept="image/*" hidden>
                  </label>
                </div>
              </div>
              <div class="info-row">
                <span class="info-label">ชื่อ</span>
                <div class="w-100">
                  <input id="firstName" name="first_name" type="text" class="form-control" value="<?php echo $first_name ?>"></input>
                  <div class="invalid-feedback"></div>
                </div>

              </div>
              <div class="info-row">
                <span class="info-label">นามสกุล</span>
                <div class="w-100">
                  <input id="lastName" name="last_name" type="text" class="form-control" value="<?php echo $last_name ?>"></input>
                  <div class="invalid-feedback"></div>
                </div>
              </div>
              <div class="info-row">
                <span class="info-label">รหัสนักศึกษา</span>
                <div class="w-100">
                  <input id="studentCode" name="student_code" type="text" class="form-control" value="<?php echo $student_code ?>" readonly></input>
                  <div class="invalid-feedback"></div>
                </div>
              </div>
              <div class="info-row">
                <span class="info-label">ระดับการศึกษา</span>
                <select name="education_level" class="form-select">
                  <?php if ($_SESSION['user']['user_type'] == USER_TYPE_ALUMNI) { ?>
                    <option <?php echo $education_level == 'ปวช.3' ? 'selected' : '' ?> value="ปวช.3">ปวช.3</option>
                    <option <?php echo $education_level == 'ปวส.2' ? 'selected' : '' ?> value="ปวส.2">ปวส.2</option>
                  <?php } else { ?>
                    <option <?php echo $education_level == 'ปวช.1' ? 'selected' : '' ?> value="ปวช.1">ปวช.1</option>
                    <option <?php echo $education_level == 'ปวช.2' ? 'selected' : '' ?> value="ปวช.2">ปวช.2</option>
                    <option <?php echo $education_level == 'ปวช.3' ? 'selected' : '' ?> value="ปวช.3">ปวช.3</option>
                    <option <?php echo $education_level == 'ปวส.1' ? 'selected' : '' ?> value="ปวส.1">ปวส.1</option>
                    <option <?php echo $education_level == 'ปวส.2' ? 'selected' : '' ?> value="ปวส.2">ปวส.2</option>
                  <?php } ?>
                </select>
              </div>
              <?php if ($_SESSION['user']['user_type'] == USER_TYPE_ALUMNI) { ?>
                <div class="info-row">
                  <span class="info-label">ปีที่จบการศึกษา</span>
                  <input name="graduation_year" type="text" class="form-control" readonly value="<?php echo $graduation_year ?>"></input>
                </div>
              <?php } else { ?>
                <input name="graduation_year" type="hidden" class="form-control" readonly value="<?php echo $graduation_year ?>"></input>
              <?php } ?>
              <?php if ($_SESSION['user']['user_type'] == USER_TYPE_ALUMNI) { ?>
                <div class="info-row">
                  <span class="info-label">สถานะการศึกษาต่อ</span>
                  <select name="status_education" class="form-select">
                    <option value="">เลือก</option>
                    <option <?php echo $status_education == 'ศึกษาต่อ' ? 'selected' : '' ?> value="ศึกษาต่อ">ศึกษาต่อ</option>
                    <option <?php echo $status_education == 'ทำงานแล้ว' ? 'selected' : '' ?> value="ทำงานแล้ว">ทำงานแล้ว</option>
                    <option <?php echo $status_education == 'ว่างงาน' ? 'selected' : '' ?> value="ว่างงาน">ว่างงาน</option>
                  </select>
                </div>
              <?php } ?>
              <div class="info-row">
                <span class="info-label">ที่อยู่</span>
                <textarea name="address" class="form-control"><?php echo $address ?></textarea>
              </div>
            </div>
          </div>

          <?php if ($_SESSION['user']['user_type'] == USER_TYPE_ALUMNI) { ?>
            <!-- Job Information -->
            <div class="detail-card">
              <div class="detail-card-header">
                <i class="fas fa-book me-2"></i>ข้อมูลการทำงาน
              </div>
              <div class="detail-card-body">
                <div class="info-row">
                  <span class="info-label">ตำแหน่งงานปัจจุบัน</span>
                  <div class="w-100">
                    <input id="currentJob" name="current_job" type="text" class="form-control" value="<?php echo $current_job ?>"></input>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
                <div class="info-row">
                  <span class="info-label">ชื่อสถานที่ทำงาน / บริษัท</span>
                  <div class="w-100">
                    <input id="currentCompany" name="current_company" type="text" class="form-control" value="<?php echo $current_company ?>"></input>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>

                <div class="info-row">
                  <span class="info-label">เงินเดือน</span>
                  <input name="current_salary" type="number" class="form-control" value="<?php echo $current_salary ?>"></input>
                </div>
              </div>
            </div>
          <?php } ?>

          <!-- Contact Information -->
          <div class="detail-card">
            <div class="detail-card-header">
              <i class="fas fa-book me-2"></i>ช่องทางการติดต่อ
            </div>
            <div class="detail-card-body">
              <div class="info-row">
                <span class="info-label">Email</span>
                <div class="w-100">
                  <input id="email" name="email" type="email" class="form-control" value="<?php echo $email ?>"></input>
                  <div class="invalid-feedback"></div>
                </div>
              </div>
              <div class="info-row">
                <span class="info-label">Telephone</span>
                <div class="w-100">
                  <input id="phone" name="phone" type="tel" class="form-control" value="<?php echo $phone ?>"></input>
                  <div class="invalid-feedback"></div>
                </div>
              </div>

              <div class="info-row">
                <span class="info-label">Facebook Link</span>
                <input name="facebook" type="text" class="form-control" value="<?php echo $facebook ?>"></input>
              </div>
              <div class="info-row">
                <span class="info-label">Instagram Link</span>
                <input name="instagram" type="text" class="form-control" value="<?php echo $instagram ?>"></input>
              </div>
              <div class="info-row">
                <span class="info-label">Line ID</span>
                <input name="line" type="text" class="form-control" value="<?php echo $line ?>"></input>
              </div>
              <div class="info-row">
                <span class="info-label">Tiktok Link</span>
                <input name="tiktok" type="text" class="form-control" value="<?php echo $tiktok ?>"></input>
              </div>
            </div>
          </div>

          <div class="text-end">
            <button type="button" id="btnCancel" class="btn btn-danger">ยกเลิก</button>
            <button type="button" id="btnSubmit" class="btn btn-success">ยินยันการแก้ไขข้อมูล</button>
          </div>
        </form>
      </div>

    </div>

  </div>

  <?php include '../includes/footer.php' ?>

  <script src="../assets/js/bootstrap.min.js"></script>
  <script src="../assets/js/sweetalert2.all.min.js"></script>
  <script src="../assets/alerts/modal.js"></script>
  <script src="../assets/js/function/update_data.js"></script>
  <script src="function/validate_form.js"></script>
  <script>
    const formShow = document.getElementById('showMode');
    const formEdit = document.getElementById('editMode');
    const btnEdit = document.getElementById('btnEdit');
    const btnCancel = document.getElementById('btnCancel');
    const btnSubmit = document.getElementById('btnSubmit');
    const formEditData = document.getElementById('formEditData');

    btnSubmit.addEventListener('click', (e) => {
      e.preventDefault();

      const formData = new FormData(formEditData);
      clearValidation('formEditData');

      if (validateForm()) {
        updateData('', formData, 'profile.php');
      }

    });

    //เมื่อกดปุ่มแก้ไขข้อมูล
    btnEdit.addEventListener('click', () => {
      formShow.style.display = 'none';
      formEdit.style.display = 'block';
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });

    })

    //เมื่อยกเลิกการแก้ไขข้อมูล
    btnCancel.addEventListener('click', () => {
      location.reload();
    })

    //เมื่อเพิ่มรูปภาพใหม่
    const input = document.getElementById('editProfilePic');

    input.addEventListener('change', function() {
      const file = this.files[0];
      const preview = document.getElementById('imagePreview');
      const deleteImage = document.getElementById('deletedImages');
      const currentImage = document.getElementById('currentImages');

      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
        };
        reader.readAsDataURL(file);

        if (currentImage.value !== '') {
          deleteImage.value = currentImage.value
        }
      }
    });

    //ตรวจสอบความถูกต้องของฟอร์ม
    function validateForm() {

      const formData = new FormData(formEditData);
      const data = Object.fromEntries(formData);

      let isValid = true;

      if (!data.student_code) {
        showFieldError('studentCode', 'รูปแบบรหัสนักศึกษาไม่ถูกต้อง');
        isValid = false;
      }

      if (!data.first_name) {
        showFieldError('firstName', 'กรุณากรอกชื่อ');
        isValid = false;
      }

      if (!data.last_name) {
        showFieldError('lastName', 'กรุณากรอกนามสกุล');
        isValid = false;
      }

      if (!validatePassword(data.email)) {
        showFieldError('email', 'รูปแบบอิเมลไม่ถูกต้อง');
        isValid = false;
      }

      if (!validatePassword(data.phone)) {
        showFieldError('phone', 'รูปแบบเบอร์โทรศัพท์ไม่ถูกต้อง');
        isValid = false;
      }

      if (!isValid) {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      }

      return isValid;
    }
  </script>
</body>

</html>