<?php
require_once '../../config/config.php';

// ตัวอย่างข้อมูลศิษย์เก่า (จริงๆ ควรดึงจากฐานข้อมูล)
$alumni_id = $_GET['id'] ?? 1;
$alumni = [
    'id' => 1,
    'name' => 'นายอัครเดช สมบูรณ์',
    'student_id' => '66120001',
    'level' => 'ปวส.',
    'year' => '2567',
    'major' => 'คอมพิวเตอร์ธุรกิจ',
    'status' => 'กำลังศึกษา',
    'email' => 'akkaradet@email.com',
    'phone' => '081-234-5678',
    'facebook' => 'akkaradet.s',
    'line' => 'akkaradet123',
    'instagram' => 'akk_sb',
    'linkedin' => 'akkaradet-somboon',
    'github' => 'akkaradet-dev',
    'address' => '123 หมู่ 5 ตำบลวิเศษไชยชาญ อำเภอวิเศษไชยชาญ จังหวัดอ่างทอง 14110',
    'birth_date' => '15 มีนาคม 2548',
    'age' => 20,
    'avatar' => 'https://bootdey.com/img/Content/avatar/avatar1.png',
    'graduation_date' => 'มีนาคม 2568 (คาดการณ์)',
    'gpa' => '3.65',
    'skills' => ['PHP', 'JavaScript', 'MySQL', 'HTML/CSS', 'Bootstrap', 'React'],
    'projects' => [
        [
            'name' => 'ระบบจัดการร้านค้าออนไลน์',
            'description' => 'พัฒนาระบบจัดการสินค้าและการขายออนไลน์ด้วย PHP และ MySQL',
            'tech' => 'PHP, MySQL, Bootstrap'
        ],
        [
            'name' => 'แอปพลิเคชันบันทึกค่าใช้จ่าย',
            'description' => 'แอปพลิเคชันสำหรับบันทึกและติดตามค่าใช้จ่ายรายวัน',
            'tech' => 'React Native, Firebase'
        ]
    ],
    'certificates' => [
        'การพัฒนาเว็บไซต์ด้วย PHP',
        'Microsoft Office Specialist',
        'Google Analytics Individual Qualification'
    ],
    'interests' => ['Web Development', 'Mobile App Development', 'UI/UX Design', 'Digital Marketing'],
    'goals' => 'ต้องการเป็น Full Stack Developer และสร้างบริษัทเทคโนโลยีของตัวเอง'
];
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
    flex-wrap: wrap;
    gap: 0.75rem;
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

  <div class="container px-4 py-3">
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom">
      <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
              <a href="#" class="text-decoration-none">
                <i class="fas fa-home"></i> หน้าแรก
              </a>
            </li>
            <li class="breadcrumb-item">
              <a href="#" class="text-decoration-none">รายชื่อศิษย์เก่า</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              นายอัครเดช สมบูรณ์
            </li>
          </ol>
        </nav>
        <button onclick="history.back()" class="btn btn-back">
          <i class="fas fa-arrow-left me-2"></i>กลับ
        </button>
      </div>
    </div>

    <div class="alumni-detail-container">
      <!-- Profile Header -->
      <div class="profile-header">
        <div class="row align-items-center">
          <div class="col-lg-3 col-md-4 text-center mb-3 mb-md-0">
            <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle profile-avatar" alt="นายอัครเดช สมบูรณ์">
          </div>
          <div class="col-lg-6 col-md-8">
            <h2 class="mb-2">นายอัครเดช สมบูรณ์</h2>
            <h5 class="mb-3 opacity-75">
              ปวส. คอมพิวเตอร์ธุรกิจ | รหัสนักศึกษา: 66120001
            </h5>
            <div class="status-badge mb-3">
              <i class="fas fa-graduation-cap me-2"></i>กำลังศึกษา
            </div>
            <p class="mb-0 opacity-75">
              <i class="fas fa-calendar me-2"></i>ปีการศึกษา 2567
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
                <span class="info-value">นายอัครเดช สมบูรณ์</span>
              </div>
              <div class="info-row">
                <span class="info-label">รหัสนักศึกษา</span>
                <span class="info-value">66120001</span>
              </div>
              <div class="info-row">
                <span class="info-label">วันเกิด</span>
                <span class="info-value">15 มีนาคม 2548 (อายุ 20 ปี)</span>
              </div>
              <div class="info-row">
                <span class="info-label">ระดับการศึกษา</span>
                <span class="info-value">ปวส. คอมพิวเตอร์ธุรกิจ</span>
              </div>
              <div class="info-row">
                <span class="info-label">ปีการศึกษา</span>
                <span class="info-value">2567</span>
              </div>
              <div class="info-row">
                <span class="info-label">วันที่จบการศึกษา</span>
                <span class="info-value">มีนาคม 2568 (คาดการณ์)</span>
              </div>
              <div class="info-row">
                <span class="info-label">เกรดเฉลี่ย</span>
                <span class="info-value">3.65</span>
              </div>
              <div class="info-row">
                <span class="info-label">ที่อยู่</span>
                <span class="info-value">123 หมู่ 5 ตำบลวิเศษไชยชาญ อำเภอวิเศษไชยชาญ จังหวัดอ่างทอง 14110</span>
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

          <!-- Projects -->
          <div class="detail-card">
            <div class="detail-card-header">
              <i class="fas fa-project-diagram me-2"></i>ผลงาน/โครงการ
            </div>
            <div class="detail-card-body">
              <div class="project-card">
                <div class="project-title">ระบบจัดการร้านค้าออนไลน์</div>
                <p class="mb-2 text-muted">พัฒนาระบบจัดการสินค้าและการขายออนไลน์ด้วย PHP และ MySQL พร้อมระบบจัดการคำสั่งซื้อและรายงานยอดขาย</p>
                <span class="project-tech">PHP, MySQL, Bootstrap, JavaScript</span>
              </div>
              <div class="project-card">
                <div class="project-title">แอปพลิเคชันบันทึกค่าใช้จ่าย</div>
                <p class="mb-2 text-muted">แอปพลิเคชันสำหรับบันทึกและติดตามค่าใช้จ่ายรายวัน พร้อมกราฟแสดงสถิติการใช้จ่าย</p>
                <span class="project-tech">React Native, Firebase, Chart.js</span>
              </div>
              <div class="project-card">
                <div class="project-title">เว็บไซต์พอร์ตโฟลิโอส่วนตัว</div>
                <p class="mb-2 text-muted">เว็บไซต์แสดงผลงานและประวัติส่วนตัว พร้อมระบบ Contact Form และ Blog</p>
                <span class="project-tech">HTML, CSS, JavaScript, PHP</span>
              </div>
            </div>
          </div>

          
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
          <!-- Contact Information -->
          <div class="detail-card">
            <div class="detail-card-header">
              <i class="fas fa-address-book me-2"></i>ช่องทางติดต่อ
            </div>
            <div class="detail-card-body">
              <div class="contact-links">
                <a href="mailto:akkaradet@email.com" class="contact-link">
                  <i class="fas fa-envelope"></i>อีเมล
                </a>
                <a href="tel:0812345678" class="contact-link">
                  <i class="fas fa-phone"></i>โทรศัพท์
                </a>
                <a href="#" class="contact-link">
                  <i class="fab fa-facebook"></i>Facebook
                </a>
                <a href="#" class="contact-link">
                  <i class="fab fa-line"></i>Line
                </a>
                <a href="#" class="contact-link">
                  <i class="fab fa-instagram"></i>Instagram
                </a>
                <a href="#" class="contact-link">
                  <i class="fab fa-linkedin"></i>LinkedIn
                </a>
                <a href="#" class="contact-link">
                  <i class="fab fa-github"></i>GitHub
                </a>
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

  <script src="../../assets/js/bootstrap.bundle.min.js"></script>
  
  </body>

</html>