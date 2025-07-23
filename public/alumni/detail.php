<?php
require_once '../../config/config.php';
require_once '../../classes/alumni.php';

$db = new Database();
$conn = $db->connect();
$alumni = new Alumni($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
  $id = $_GET['id'];

  $alumni_detail = $alumni->getAlumni($id);

  $student_code = $alumni_detail['student_code'];
  $fullname = $alumni_detail['first_name'] . ' ' . $alumni_detail['last_name'];
  $image = $alumni_detail['image'];
  $education_level = $alumni_detail['education_level'];
  $graduation_year = $alumni_detail['graduation_year'];

  $email = $alumni_detail['email'];
  $phone = $alumni_detail['phone'];
  $address = !empty($alumni_detail['address']) ? $alumni_detail['address'] : "ไม่มีข้อมูล";
  $facebook = $alumni_detail['facebook'];
  $instagram = $alumni_detail['instagram'];
  $tiktok = $alumni_detail['tiktok'];
  $line = $alumni_detail['line'];

  $status_education = !empty($alumni_detail['status_education']) ? $alumni_detail['status_education'] : 'ไม่มีข้อมูล';
} else {
  header("Location : index.php");
  exit;
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
    height: 150px;
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
  }

  .detail-card-header {
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
      height: 120px;
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
  <?php include '../../includes/navbar.php' ?>

  <div class="container-xxl py-3">

    <button onclick="history.back()" class="btn btn-back mb-3">
      <i class="fas fa-arrow-left me-2"></i>กลับ
    </button>

    <div class="alumni-detail-container">
      <!-- Profile Header -->
      <div class="profile-header">
        <div class="row align-items-center">
          <div class="col-lg-3 col-md-4 text-center mb-3 mb-md-0">
            <img src="../../assets/images/user/alumni/<?php echo $image; ?>" class="rounded-circle profile-avatar" alt="<?php echo $fullname ?>">
          </div>
          <div class="col-lg-6 col-md-8">
            <h2 class="mb-2"><?php echo $fullname ?></h2>
            <h5 class="mb-3 opacity-75">
              ระดับชั้นที่จบการศึกษา : <?php $education_level ?> คอมพิวเตอร์ธุรกิจ | รหัสนักศึกษา: <?php echo $student_code ?>
            </h5>
            <div class="status-badge mb-3">
              <i class="fas fa-graduation-cap me-2"></i>สถานะการศึกษา : <?php echo $status_education ?>
            </div>
            <p class="mb-0 opacity-75">
              <i class="fas fa-calendar me-2"></i>ปีที่จบการศึกษา <?php echo $graduation_year ?>
            </p>
          </div>
          <div class="col-lg-3 text-center">
            <div class="gpa-circle">
              3.65
            </div>
            <small class="d-block mt-2 opacity-75">เกรดเฉลี่ย</small>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
          <!-- Personal Information -->
          <div class="detail-card">
            <div class="detail-card-header">
              <i class="fas fa-user me-2"></i>ข้อมูลส่วนตัว
            </div>
            <div class="detail-card-body">
              <div class="info-row">
                <span class="info-label">ชื่อ-นามสกุล</span>
                <span class="info-value"><?php echo $fullname ?></span>
              </div>
              <div class="info-row">
                <span class="info-label">รหัสนักศึกษา</span>
                <span class="info-value"><?php echo $student_code ?></span>
              </div>
              <div class="info-row">
                <span class="info-label">วันเกิด</span>
                <span class="info-value">15 มีนาคม 2548 (อายุ 20 ปี)</span>
              </div>
              <div class="info-row">
                <span class="info-label">ระดับการศึกษา</span>
                <span class="info-value"><?php echo $education_level ?></span>
              </div>
              <div class="info-row">
                <span class="info-label">ปีการศึกษา</span>
                <span class="info-value"><?php echo $graduation_year ?></span>
              </div>
              <div class="info-row">
                <span class="info-label">เกรดเฉลี่ย</span>
                <span class="info-value">3.65</span>
              </div>
              <div class="info-row">
                <span class="info-label">ที่อยู่</span>
                <span class="info-value"><?php echo $address ?></span>
              </div>
            </div>
          </div>

          <!-- Skills -->
          <div class="detail-card">
            <div class="detail-card-header">
              <i class="fas fa-tools me-2"></i>ทักษะความสามารถ
            </div>
            <div class="detail-card-body">
              <span class="skill-badge">PHP</span>
              <span class="skill-badge">JavaScript</span>
              <span class="skill-badge">MySQL</span>
              <span class="skill-badge">HTML/CSS</span>
              <span class="skill-badge">Bootstrap</span>
              <span class="skill-badge">React</span>
              <span class="skill-badge">Node.js</span>
              <span class="skill-badge">Python</span>
            </div>
          </div>


        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
          <!-- Contact Information -->
          <div class="detail-card">
            <div class="detail-card-header">
              <i class="fas fa-address-book me-2"></i>ช่องทางการติดต่อ
            </div>
            <div class="detail-card-body">
              <div class="contact-links">
                <a href="mailto:<?php echo $email ?>" class="contact-link">
                  <i class="fas fa-envelope"></i>อีเมลล์ : <?php echo $email ?>
                </a>
                <a href="tel:<?php echo $phone ?>" class="contact-link">
                  <i class="fas fa-phone"></i>โทรศัพท์ : <?php echo $phone ?>
                </a>
                <?php if (!empty($facebook)) { ?>
                  <a href="<?php echo $facebook ?>" target="_blank" class="contact-link">
                    <i class="fab fa-facebook"></i>Facebook
                  </a>
                <?php } ?>
                <?php if (!empty($line)) { ?>
                <a href="<?php echo $line ?>" class="contact-link">
                  <i class="fab fa-line"></i>Line
                </a>
                <?php } ?>
                <?php if (!empty($instagram)) { ?>
                <a href="<?php echo $instagram ?>" class="contact-link">
                  <i class="fab fa-instagram"></i>Instagram
                </a>
                <?php } ?>
                <?php if (!empty($tiktok)) { ?>
                <a href="<?php echo $tiktok ?>" class="contact-link">
                  <i class="fa-brands fa-tiktok"></i>Tiktok
                </a>
                <?php } ?>
              </div>
            </div>
          </div>

          <!-- Certificates -->
          <div class="detail-card">
            <div class="detail-card-header">
              <i class="fas fa-certificate me-2"></i>ใบประกาศนียบัตร/รางวัล
            </div>
            <div class="detail-card-body">
              <div class="certificate-item">
                <i class="fas fa-award text-primary me-2"></i>การพัฒนาเว็บไซต์ด้วย PHP
              </div>
              <div class="certificate-item">
                <i class="fas fa-award text-primary me-2"></i>Microsoft Office Specialist
              </div>
              <div class="certificate-item">
                <i class="fas fa-award text-primary me-2"></i>Google Analytics Individual Qualification
              </div>
              <div class="certificate-item">
                <i class="fas fa-award text-primary me-2"></i>รางวัลโครงการดีเด่น ปีการศึกษา 2566
              </div>
            </div>
          </div>




        </div>
      </div>
    </div>
  </div>

  <?php include '../../includes/footer.php' ?>

  <script src="../../assets/js/bootstrap.min.js"></script>

</body>

</html>