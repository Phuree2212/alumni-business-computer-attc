<?php
require_once '../config/config.php';
require_once '../classes/user.php';

$db = new Database();
$conn = $db->connect();

$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user_type = $_POST['user_type'] ?? '';
  $student_code = (int)$_POST['student_code'] ?? '';
  $first_name = $_POST['first_name'] ?? '';
  $last_name = $_POST['last_name'] ?? '';
  $email = $_POST['email'] ?? '';
  $phone = $_POST['phone'] ?? '';
  $education_level = $_POST['education_level'] ?? '';
  $graduation_year = $_POST['graduation_year'] ?? '';
  $password = $_POST['password'] ?? '';

  $result = $user->register($student_code, $first_name, $last_name, $password, $email, $phone, $user_type, $education_level, $graduation_year);

  // ตั้งค่าหัวข้อว่าเป็น JSON
  header('Content-Type: application/json');

  echo json_encode($result);
  exit;
}

?>
<!DOCTYPE html>
<html lang="th">

<head>
  <?php include '../includes/title.php' ?>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/css/sweetalert2.min.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">

</head>
<style>
  .tab-content {
    border: 1px solid #dee2e6;
    border-top: none;
    padding: 2rem;
    border-radius: 0 0 0.375rem 0.375rem;
  }

  .nav-tabs .nav-link {
    color: #6c757d;
    border: 1px solid transparent;
    border-radius: 0.375rem 0.375rem 0 0;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    position: relative;
  }

  .nav-tabs .nav-link:hover {
    color: #0d6efd;
    border-color: #e9ecef #e9ecef #dee2e6;
    background-color: #f8f9fa;
  }

  .nav-tabs .nav-link.active {
    color: #0d6efd;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
  }

  .nav-tabs .nav-link i {
    margin-right: 0.5rem;
  }

  .form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
  }

  .form-control,
  .form-select {
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
    padding: 0.75rem 1rem;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
  }

  .btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    padding: 0.75rem 2rem;
    font-weight: 500;
  }

  .btn-primary:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
  }

  .bg-light {
    background-color: #f8f9fa !important;
  }

  .shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
  }

  .card {
    border: none;
    border-radius: 0.5rem;
  }

  .tab-pane {
    animation: fadeIn 0.3s ease-in-out;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>

<body>
  <?php include '../includes/navbar.php' ?>
  <!-- ฟอร์มลงทะเบียน -->
  <!-- Registration 2 - Bootstrap Brain Component -->
  <div class="bg-light py-3 py-md-5">
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="col-12 col-md-11 col-lg-8 col-xl-7 col-xxl-6">
          <div class="card shadow-sm">
            <div class="card-body p-4 p-md-5">
              <div class="row">
                <div class="col-12">
                  <div class="mb-4">
                    <h2 class="h3 mb-2 text-center">ลงทะเบียนสมาชิก</h2>
                    <p class="text-muted mb-0 text-center">เลือกประเภทผู้ใช้งานและกรอกข้อมูลให้ครบถ้วน</p>
                  </div>
                </div>
              </div>

              <!-- Tab Navigation -->
              <ul class="nav nav-tabs" id="userTypeTabs" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="alumni-tab" data-bs-toggle="tab" data-bs-target="#alumni" type="button" role="tab" aria-controls="alumni" aria-selected="true">
                    <i class="bi bi-mortarboard"></i>ศิษย์เก่า
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="student-tab" data-bs-toggle="tab" data-bs-target="#student" type="button" role="tab" aria-controls="student" aria-selected="false">
                    <i class="bi bi-person-workspace"></i>ศิษย์ปัจจุบัน
                  </button>
                </li>
              </ul>

              <!-- Tab Content -->
              <div class="tab-content" id="userTypeTabsContent">
                <!-- Alumni Form -->
                <div class="tab-pane fade show active" id="alumni" role="tabpanel" aria-labelledby="alumni-tab">
                  <form method="post" class="needs-validation" id="alumniForm" novalidate>
                    <div class="row gy-3 gy-md-4">
                      <input type="hidden" value="alumni" name="user_type">
                      <div class="col-12">
                        <label for="alumniStudentCode" class="form-label">รหัสนักศึกษา<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="student_code" id="alumniStudentCode" placeholder="รหัสนักศึกษา" required>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="col-md-6">
                        <label for="alumniFirstName" class="form-label">ชื่อ<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="first_name" id="alumniFirstName" placeholder="ชื่อ" required>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="col-md-6">
                        <label for="alumniLastName" class="form-label">นามสกุล<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="last_name" id="alumniLastName" placeholder="นามสกุล" required>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="col-md-6">
                        <label for="alumniEmail" class="form-label">อีเมล<span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" id="alumniEmail" placeholder="name@example.com" required>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="col-md-6">
                        <label for="alumniPhone" class="form-label">เบอร์โทรศัพท์<span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" name="phone" id="alumniPhone" placeholder="xxx-xxxxxxx" required>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="col-md-6">
                        <label for="alumniEducationLevel" class="form-label">ระดับชั้นที่จบการศึกษา<span class="text-danger">*</span></label>
                        <select class="form-select" name="education_level" id="alumniEducationLevel" required>
                          <option value="">เลือกระดับการศึกษา</option>
                          <option value="ปวช.3">ปวช.3</option>
                          <option value="ปวส.2">ปวส.2</option>
                        </select>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="col-md-6">
                        <label for="alumniGraduationYear" class="form-label">ปีที่จบการศึกษา<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="graduation_year" id="alumniGraduationYear" placeholder="25xx" required>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="col-md-6">
                        <label for="alumniPassword" class="form-label">รหัสผ่าน<span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" id="alumniPassword" required>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="col-md-6">
                        <label for="alumniConfirmPassword" class="form-label">ยืนยันรหัสผ่าน<span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="confirm_password" id="alumniConfirmPassword" required>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="col-12">
                        <div class="d-grid">
                          <button class="btn btn-lg btn-primary" type="submit">
                            <i class="bi bi-person-plus me-2"></i>ลงทะเบียนศิษย์เก่า
                          </button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>

                <!-- Student Form -->
                <div class="tab-pane fade" id="student" role="tabpanel" aria-labelledby="student-tab">
                  <form method="post" id="studentForm" class="needs-validation" novalidate>
                    <div class="row gy-3 gy-md-4">
                      <input type="hidden" value="student" name="user_type">
                      <div class="col-12">
                        <label for="studentStudentCode" class="form-label">รหัสนักศึกษา<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="student_code" id="studentStudentCode" placeholder="รหัสนักศึกษา" required>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="col-md-6">
                        <label for="studentFirstName" class="form-label">ชื่อ<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="first_name" id="studentFirstName" placeholder="ชื่อ" required>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="col-md-6">
                        <label for="studentLastName" class="form-label">นามสกุล<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="last_name" id="studentLastName" placeholder="นามสกุล" required>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="col-md-6">
                        <label for="studentEmail" class="form-label">อีเมล<span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" id="studentEmail" placeholder="name@example.com" required>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="col-md-6">
                        <label for="studentPhone" class="form-label">เบอร์โทรศัพท์<span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" name="phone" id="studentPhone" placeholder="xxx-xxxxxxx" required>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="col-12">
                        <label for="studentCurrentLevel" class="form-label">ระดับชั้นที่กำลังศึกษา<span class="text-danger">*</span></label>
                        <select class="form-select" name="education_level" id="studentEducationLevel" required>
                          <option value="">เลือกระดับชั้น</option>
                          <option value="ปวช.1">ปวช.1</option>
                          <option value="ปวช.2">ปวช.2</option>
                          <option value="ปวช.3">ปวช.3</option>
                          <option value="ปวส.1">ปวส.1</option>
                          <option value="ปวส.2">ปวส.2</option>
                        </select>
                        <div class="invalid-feedback"></div>
                      </div>
                      <div class="col-md-6">
                        <label for="studentPassword" class="form-label">รหัสผ่าน<span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" id="studentPassword" required>
                        <div class="invalid-feedback"></div>
                      </div>

                      <div class="col-md-6">
                        <label for="studentConfirmPassword" class="form-label">ยืนยันรหัสผ่าน<span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="confirm_password" id="studentConfirmPassword" required>
                        <div class="invalid-feedback"></div>
                      </div>


                      <div class="col-12">
                        <div class="d-grid">
                          <button class="btn btn-lg btn-primary" type="submit">
                            <i class="bi bi-person-plus me-2"></i>ลงทะเบียนศิษย์ปัจจุบัน
                          </button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>

              <div class="row">
                <div class="col-12">
                  <hr class="mt-4 mb-4 border-secondary-subtle">
                  <div class="col-12">
                    <p class="m-0 text-secondary text-center">
                      คุณมีบัญชีแล้วใช่ไหม?
                      <a href="login.php" class="link-primary text-decoration-none">เข้าสู่ระบบ</a>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include '../includes/footer.php' ?>

  <script src="../assets/js/bootstrap.bundle.js"></script>
  <script src="../assets/js/sweetalert2.all.min.js"></script>
  <script src="../assets/alerts/modal.js"></script>
  <script src="function/validate_form.js"></script>
  <script>
    // Unified form validation function
    function validateUserForm(formId) {
      const form = document.getElementById(formId);
      const formData = new FormData(form);
      const data = Object.fromEntries(formData);
      const isAlumni = formId === 'alumniForm';
      const prefix = isAlumni ? 'alumni' : 'student';

      clearValidation(formId);

      const validations = [{
          field: 'student_code',
          validator: validateStudentCode,
          message: 'รูปแบบรหัสนักศึกษาไม่ถูกต้อง'
        },
        {
          field: 'first_name',
          validator: (val) => val.trim(),
          message: 'กรุณาระบุชื่อ'
        },
        {
          field: 'last_name',
          validator: (val) => val.trim(),
          message: 'กรุณาระบุนามสกุล'
        },
        {
          field: 'email',
          validator: validateEmail,
          message: 'รูปแบบอีเมลไม่ถูกต้อง'
        },
        {
          field: 'phone',
          validator: validatePhone,
          message: 'รูปแบบเบอร์โทรศัพท์ไม่ถูกต้อง'
        },
        {
          field: 'education_level',
          validator: (val) => val,
          message: 'กรุณาเลือกระดับการศึกษา'
        },
        {
          field: 'password',
          validator: validatePassword,
          message: 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร'
        }
      ];

      // Add graduation year validation for alumni only
      if (isAlumni) {
        validations.push({
          field: 'graduation_year',
          validator: validateYear,
          message: 'ปีที่จบการศึกษาไม่ถูกต้อง'
        });
      }

      let isValid = true;

      // Run validations
      validations.forEach(({
        field,
        validator,
        message
      }) => {
        if (!validator(data[field])) {
          showFieldError(`${prefix}${field.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join('')}`, message);
          isValid = false;
        }
      });

      // Check password confirmation
      if (data.password !== data.confirm_password) {
        showFieldError(`${prefix}ConfirmPassword`, 'รหัสผ่านไม่ตรงกัน');
        isValid = false;
      }

      return isValid;
    }

    // Unified form submission handler
    function handleFormSubmit(formId) {
      const form = document.getElementById(formId);

      form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (validateUserForm(formId)) {
          const formData = new FormData(this);
          const body = new URLSearchParams(formData).toString();

          fetch('register.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: body
            })
            .then(response => response.json())
            .then(response => {
              if (response.result === true) {
                modalAlert('ลงทะเบียนสมาชิกสำเร็จ', 'กรุณารอการตรวจสอบข้อมูลเพื่ออนุมัติการใช้งาน จากผู้ดูแลระบบภายใน 24 ชั่วโมง ขอบคุณที่ร่วมลงทะเบียนกับเรา', 'success')
                  .then(() => location.reload());
              } else {
                modalAlert('ลงทะเบียนไม่สำเร็จ', response.message, 'error');
              }
            })
            .catch(error => {
              modalAlert('การเชื่อมต่อล้มเหลว', 'ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้', 'error');
              console.error('Fetch error:', error);
            });
        }
      });
    }

    // Initialize form handlers
    ['alumniForm', 'studentForm'].forEach(handleFormSubmit);
    
  </script>
</body>

</html>