<?php
require_once '../../config/config.php';
require_once '../../classes/alumni.php';
require_once '../../classes/pagination_helper.php';

$db = new Database();
$conn = $db->connect();
$alumni = new Alumni($conn);

//$alumni_list = $alumni->getAllAlumni(100, 0);

// ตั้งค่าพื้นฐาน
$currentPage   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage  = 10;

// รับค่าการค้นหา
$keyword    = trim($_GET['keyword'] ?? '');
$education_level = $_GET['education_level'] ?? '';
$graduation_year = $_GET['graduation_year'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date   = $_GET['end_date'] ?? '';

// ถ้ามีการกรองข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'GET' && (!empty($keyword) || !empty($education_level) || !empty($graduation_year) || !empty($start_date) || !empty($end_date))) {

  // นับจำนวนรายการที่ตรงกับเงื่อนไขการค้นหา
  $totalItems = $alumni->getSearchAndFilterCount($keyword, $education_level, $graduation_year, $start_date, $end_date);

  // สร้าง pagination
  $pagination = new PaginationHelper($currentPage, $itemsPerPage, $totalItems);

  // ดึงข่าวตามเงื่อนไข
  $alumni_list = $alumni->searchAndFilterAlumni($keyword, $education_level, $graduation_year, $start_date, $end_date, $pagination->getLimit(), $pagination->getOffset());
} else {
  // นับจำนวนรายการทั้งหมด
  $totalItems = $alumni->getTotalCount();

  // สร้าง pagination
  $pagination = new PaginationHelper($currentPage, $itemsPerPage, $totalItems);

  // ดึงข่าวทั้งหมด
  $alumni_list = $alumni->getAllAlumni(
    $pagination->getLimit(),
    $pagination->getOffset()
  );
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
  .alumni-container {
    min-height: calc(100vh - 3.5rem);
    background-color: #f8f9fa;
  }

  .sidebar-nav {
    background-color: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1);
    padding: 1.5rem;
  }

  .main-content {
    background-color: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1);
    padding: 2rem;
    min-height: calc(100vh - 8rem);
  }

  .alumni-card {
    background-color: #fff;
    border: 1px solid #e9ecef;
    border-radius: 0.75rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    overflow: hidden;
  }

  .alumni-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #467bcb;
  }

  .alumni-avatar {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  .alumni-info h5 {
    color: #1f2937;
    font-weight: 600;
    margin-bottom: 0.5rem;
  }

  .alumni-info .text-muted {
    font-size: 0.875rem;
  }

  .alumni-badge {
    background: linear-gradient(135deg, #467bcb 0%, #5a8dd8 100%);
    color: white;
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-weight: 500;
  }

  .search-section {
    background: linear-gradient(135deg, #467bcb 0%, #5a8dd8 100%);
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
  }

  .search-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }

  .nav-pills .nav-link {
    color: #4a5568;
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .nav-pills .nav-link:hover {
    background-color: #e2e8f0;
  }

  .nav-pills .nav-link.active {
    background: linear-gradient(135deg, #467bcb 0%, #5a8dd8 100%);
    color: #fff;
    box-shadow: 0 2px 8px rgba(70, 123, 203, 0.3);
  }

  .stats-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    border-radius: 1rem;
    padding: 1.5rem;
    text-align: center;
    transition: transform 0.3s ease;
  }

  .stats-card:hover {
    transform: translateY(-2px);
  }

  .stats-number {
    font-size: 2rem;
    font-weight: 700;
    color: #467bcb;
    margin-bottom: 0.5rem;
  }

  .alumni-contact {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
  }

  .contact-btn {
    padding: 0.375rem 0.5rem;
    border-radius: 0.5rem;
    border: 1px solid #dee2e6;
    background: #fff;
    color: #6c757d;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.875rem;
  }

  .contact-btn:hover {
    background: #467bcb;
    color: white;
    border-color: #467bcb;
    text-decoration: none;
  }

  .year-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(70, 123, 203, 0.1);
    color: #467bcb;
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-weight: 600;
  }

  .filter-section {
    background: #fff;
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid #e9ecef;
  }

  .accordion-button {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #495057;
    font-weight: 600;
  }

  .accordion-button:not(.collapsed) {
    background-color: #467bcb;
    color: white;
    border-color: #467bcb;
  }

  .accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(70, 123, 203, 0.25);
  }

  @media (max-width: 767.98px) {
    .main-content {
      padding: 1rem;
    }

    .search-section {
      padding: 1.5rem;
    }

    .alumni-avatar {
      width: 60px;
      height: 60px;
    }

    .alumni-contact {
      justify-content: center;
    }
  }
</style>

<body>
  <?php include '../../includes/navbar.php' ?>

  <div class="container-xxl py-3">
    <div class="row alumni-container">
      <!-- Sidebar -->
      <div class="col-lg-3 col-md-4">
        <!-- Desktop Sidebar -->
        <div class="d-none d-lg-block">
          <div class="sidebar-nav">
            <h5 class="mb-4">
              <i class="fas fa-users me-2 text-primary"></i>
              รายชื่อศิษย์เก่า
            </h5>

            <!-- Statistics -->
            <div class="row g-2 mb-4">
              <div class="col-12">
                <div class="stats-card">
                  <div class="stats-number"><?php echo $totalItems ?></div>
                  <small class="text-muted">สมาขิกศิษย์เก่า</small>
                </div>
              </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="nav nav-pills flex-column">
              <a href="javascript:void(0)" class="nav-link active">
                <i class="fas fa-list me-2"></i>ทั้งหมด
              </a>
              <a href="javascript:void(0)" class="nav-link">
                <i class="fas fa-certificate me-2"></i>ปวช.
              </a>
              <a href="javascript:void(0)" class="nav-link">
                <i class="fas fa-graduation-cap me-2"></i>ปวส.
              </a>
              <a href="javascript:void(0)" class="nav-link">
                <i class="fas fa-briefcase me-2"></i>ทำงานแล้ว
              </a>
            </nav>
          </div>
        </div>

        <!-- Mobile/Tablet Accordion -->
        <div class="d-lg-none mb-3">
          <div class="accordion" id="sidebarAccordion">
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilter" aria-expanded="true">
                  <i class="fas fa-filter me-2"></i>
                  ตัวกรอง
                </button>
              </h2>
              <div id="collapseFilter" class="accordion-collapse collapse show" data-bs-parent="#sidebarAccordion">
                <div class="accordion-body p-2">
                  <nav class="nav nav-pills flex-column">
                    <a href="javascript:void(0)" class="nav-link active">
                      <i class="fas fa-list me-2"></i>ทั้งหมด
                    </a>
                    <a href="javascript:void(0)" class="nav-link">
                      <i class="fas fa-star me-2"></i>รุ่นใหม่ล่าสุด
                    </a>
                    <a href="javascript:void(0)" class="nav-link">
                      <i class="fas fa-crown me-2"></i>รุ่นอาวุโส
                    </a>
                    <a href="javascript:void(0)" class="nav-link">
                      <i class="fas fa-certificate me-2"></i>ปวช.
                    </a>
                    <a href="javascript:void(0)" class="nav-link">
                      <i class="fas fa-graduation-cap me-2"></i>ปวส.
                    </a>
                    <a href="javascript:void(0)" class="nav-link">
                      <i class="fas fa-briefcase me-2"></i>ทำงานแล้ว
                    </a>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="col-lg-9 col-md-8">
        <div class="main-content">
          <!-- Search Section -->
          <div class="search-section">
            <div class="text-center mb-3">
              <h2 class="mb-2">
                <i class="fas fa-search me-2"></i>
                รวมสมาชิกศิษย์เก่า
              </h2>
              <p class="mb-0 opacity-75">แผนกวิชาคอมพิวเตอร์ธุรกิจ วิทยาลัยเทคนนิคอ่างทอง</p>
            </div>

            <div class="search-card">
              <form action="index.php" method="get">
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label text-dark">ชื่อ-นามสกุล</label>
                    <input type="text" name="keyword" value="<?php echo htmlspecialchars($keyword ?? ''); ?>" class="form-control" id="searchName" placeholder="ค้นหาชื่อ...">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label text-dark">ปีที่จบการศึกษา</label>
                    <select name="graduation_year" class="form-select">
                        <option selected value="">ทั้งหมด</option>
                        <?php $year = 2540; ?>
                        <?php for($i = $year ; $i <= date('Y') + 543;  $i++){ ?>
                        <option <?php echo $graduation_year == $i ? 'selected' : '' ?> value="<?php echo $i ?>"><?php echo $i ?></option>    
                        <?php } ?>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label text-dark">ระดับชั้นที่จบการศึกษา</label>
                    <select class="form-select" name="education_level" id="searchLevel">
                      <option selected value="">ทั้งหมด</option>
                        <option <?php echo $education_level == 'ปวช.3' ? 'selected' : '' ?> value="ปวช.3">ปวช.3</option>    
                        <option <?php echo $education_level == 'ปวส.2' ? 'selected' : '' ?> value="ปวส.2">ปวส.2</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label class="form-label text-dark">&nbsp;</label>
                    <button class="btn btn-primary w-100" type="submit">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <!-- Filter Section -->
          <div class="filter-section">
            <div class="row align-items-center">
              <div class="col-md-6">
                <div class="d-flex align-items-center">
                  <span class="me-3 text-muted">เรียงโดย:</span>
                  <select class="form-select form-select-sm" style="width: auto;" onchange="sortAlumni(this.value)">
                    <option value="name">ชื่อ A-Z</option>
                    <option value="year">ปีการศึกษา</option>
                    <option value="level">ระดับชั้น</option>
                    <option value="recent">เพิ่มล่าสุด</option>
                  </select>
                </div>
              </div>

            </div>
          </div>

          <!-- Alumni Grid -->
          <div id="alumniGrid" class="row g-4">
            <!-- Alumni Card 1 -->
            <?php if (!empty($alumni_list)) { ?>
              <?php foreach ($alumni_list as $item) {
                $id = $item['user_id'];
                $fullname = $item['first_name'] . ' ' . $item['last_name'];
                $image = $item['image'];
                $education_level = $item['education_level'];
                $graduation_year = $item['graduation_year'];
                $status_education = !empty($item['status_education']) ? $item['status_education'] : 'ไม่มีข้อมูล';

              ?>
                <div class="col-lg-4 col-md-6 alumni-item">
                  <a class="nav-link" href="detail.php?id=<?php echo $id ?>">
                    <div class="card alumni-card h-100 position-relative">
                      <div class="year-badge"><?php echo $graduation_year ?></div>
                      <div class="card-body text-center">
                        <img src="../../assets/images/user/alumni/<?php echo $image ?>" class="rounded-circle alumni-avatar mx-auto mb-3" alt="Alumni">
                        <div class="alumni-info">
                          <h5><?php echo $fullname ?></h5>
                          <p class="text-muted mb-2"><?php echo $education_level ?> คอมพิวเตอร์ธุรกิจ</p>
                          <span class="alumni-badge"><?php echo $status_education ?></span>
                          
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
            <?php }
            } ?>



            <!-- Pagination -->
            <div class="pagination-wrapper">
              <div class="row align-items-center">
                <?php

                echo $pagination->renderBootstrap();

                ?>
              </div>
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