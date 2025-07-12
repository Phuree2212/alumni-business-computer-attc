<?php
require_once '../../config/config.php';
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

  <div class="container px-4 py-3">
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
                  <div class="stats-number">1,247</div>
                  <small class="text-muted">สมาขิกศิษย์เก่า</small>
                </div>
              </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="nav nav-pills flex-column">
              <a href="javascript:void(0)" class="nav-link active" onclick="filterAlumni('all')">
                <i class="fas fa-list me-2"></i>ทั้งหมด
              </a>
              <a href="javascript:void(0)" class="nav-link" onclick="filterAlumni('certificate')">
                <i class="fas fa-certificate me-2"></i>ปวช.
              </a>
              <a href="javascript:void(0)" class="nav-link" onclick="filterAlumni('diploma')">
                <i class="fas fa-graduation-cap me-2"></i>ปวส.
              </a>
              <a href="javascript:void(0)" class="nav-link" onclick="filterAlumni('employed')">
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
                    <a href="javascript:void(0)" class="nav-link active" onclick="filterAlumni('all')">
                      <i class="fas fa-list me-2"></i>ทั้งหมด
                    </a>
                    <a href="javascript:void(0)" class="nav-link" onclick="filterAlumni('recent')">
                      <i class="fas fa-star me-2"></i>รุ่นใหม่ล่าสุด
                    </a>
                    <a href="javascript:void(0)" class="nav-link" onclick="filterAlumni('senior')">
                      <i class="fas fa-crown me-2"></i>รุ่นอาวุโส
                    </a>
                    <a href="javascript:void(0)" class="nav-link" onclick="filterAlumni('certificate')">
                      <i class="fas fa-certificate me-2"></i>ปวช.
                    </a>
                    <a href="javascript:void(0)" class="nav-link" onclick="filterAlumni('diploma')">
                      <i class="fas fa-graduation-cap me-2"></i>ปวส.
                    </a>
                    <a href="javascript:void(0)" class="nav-link" onclick="filterAlumni('employed')">
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
                รวมสมาขิกศิษย์เก่า
              </h2>
              <p class="mb-0 opacity-75">แผนกวิชาคอมพิวเตอร์ธุรกิจ วิทยาลัยเทคนนิคอ่างทอง</p>
            </div>

            <div class="search-card">
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label text-dark">ชื่อ-นามสกุล</label>
                  <input type="text" class="form-control" id="searchName" placeholder="ค้นหาชื่อ...">
                </div>
                <div class="col-md-3">
                  <label class="form-label text-dark">ปีการศึกษา</label>
                  <select class="form-select" id="searchYear">
                    <option value="">ทั้งหมด</option>
                    <option value="2567">2567</option>
                    <option value="2566">2566</option>
                    <option value="2565">2565</option>
                    <option value="2564">2564</option>
                    <option value="2563">2563</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label text-dark">ระดับชั้น</label>
                  <select class="form-select" id="searchLevel">
                    <option value="">ทั้งหมด</option>
                    <option value="ปวช.">ปวช.</option>
                    <option value="ปวส.">ปวส.</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label class="form-label text-dark">&nbsp;</label>
                  <button class="btn btn-primary w-100" onclick="searchAlumni()">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
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
              <div class="col-md-6">
                <div class="d-flex justify-content-md-end align-items-center">
                  <span class="me-2 text-muted">แสดง:</span>
                  <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary active" onclick="setView('grid')">
                      <i class="fas fa-th"></i>
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="setView('list')">
                      <i class="fas fa-list"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Alumni Grid -->
          <div id="alumniGrid" class="row g-4">
            <!-- Alumni Card 1 -->
            <div class="col-lg-4 col-md-6 alumni-item" data-year="2567" data-level="ปวส." data-name="นายอัครเดช สมบูรณ์">
              <div class="card alumni-card h-100 position-relative">
                <div class="year-badge">2567</div>
                <div class="card-body text-center">
                  <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle alumni-avatar mx-auto mb-3" alt="Alumni">
                  <div class="alumni-info">
                    <h5>นายอัครเดช สมบูรณ์</h5>
                    <p class="text-muted mb-2">ปวส.3 คอมพิวเตอร์ธุรกิจ</p>
                    <span class="alumni-badge">กำลังศึกษา</span>
                    <div class="alumni-contact">
                      <a href="mailto:akkaradet@email.com" class="contact-btn">
                        <i class="fas fa-envelope"></i>
                      </a>
                      <a href="tel:0812345678" class="contact-btn">
                        <i class="fas fa-phone"></i>
                      </a>
                      <a href="#" class="contact-btn">
                        <i class="fab fa-facebook"></i>
                      </a>
                      <a href="#" class="contact-btn">
                        <i class="fab fa-line"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Alumni Card 2 -->
            <div class="col-lg-4 col-md-6 alumni-item" data-year="2566" data-level="ปวส." data-name="นางสาวสุพิชญา แสงทอง">
              <div class="card alumni-card h-100 position-relative">
                <div class="year-badge">2566</div>
                <div class="card-body text-center">
                  <img src="https://bootdey.com/img/Content/avatar/avatar2.png" class="rounded-circle alumni-avatar mx-auto mb-3" alt="Alumni">
                  <div class="alumni-info">
                    <h5>นางสาวสุพิชญา แสงทอง</h5>
                    <p class="text-muted mb-2">ปวส. คอมพิวเตอร์ธุรกิจ</p>
                    <span class="alumni-badge">ทำงานแล้ว</span>
                    <p class="mt-2 mb-2 text-muted small">Web Developer @ ABC Company</p>
                    <div class="alumni-contact">
                      <a href="mailto:supitchaya@email.com" class="contact-btn">
                        <i class="fas fa-envelope"></i>
                      </a>
                      <a href="tel:0823456789" class="contact-btn">
                        <i class="fas fa-phone"></i>
                      </a>
                      <a href="#" class="contact-btn">
                        <i class="fab fa-facebook"></i>
                      </a>
                      <a href="#" class="contact-btn">
                        <i class="fab fa-linkedin"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Alumni Card 3 -->
            <div class="col-lg-4 col-md-6 alumni-item" data-year="2565" data-level="ปวช." data-name="นายธนากร เจริญสุข">
              <div class="card alumni-card h-100 position-relative">
                <div class="year-badge">2565</div>
                <div class="card-body text-center">
                  <img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle alumni-avatar mx-auto mb-3" alt="Alumni">
                  <div class="alumni-info">
                    <h5>นายธนากร เจริญสุข</h5>
                    <p class="text-muted mb-2">ปวช. คอมพิวเตอร์ธุรกิจ</p>
                    <span class="alumni-badge">ทำงานแล้ว</span>
                    <p class="mt-2 mb-2 text-muted small">IT Support @ XYZ Ltd.</p>
                    <div class="alumni-contact">
                      <a href="mailto:thanakorn@email.com" class="contact-btn">
                        <i class="fas fa-envelope"></i>
                      </a>
                      <a href="tel:0834567890" class="contact-btn">
                        <i class="fas fa-phone"></i>
                      </a>
                      <a href="#" class="contact-btn">
                        <i class="fab fa-facebook"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Alumni Card 4 -->
            <div class="col-lg-4 col-md-6 alumni-item" data-year="2566" data-level="ปวช." data-name="นางสาวพิมพ์ใจ รักดี">
              <div class="card alumni-card h-100 position-relative">
                <div class="year-badge">2566</div>
                <div class="card-body text-center">
                  <img src="https://bootdey.com/img/Content/avatar/avatar4.png" class="rounded-circle alumni-avatar mx-auto mb-3" alt="Alumni">
                  <div class="alumni-info">
                    <h5>นางสาวพิมพ์ใจ รักดี</h5>
                    <p class="text-muted mb-2">ปวช. คอมพิวเตอร์ธุรกิจ</p>
                    <span class="alumni-badge">ศึกษาต่อ</span>
                    <p class="mt-2 mb-2 text-muted small">ปวส. มหาวิทยาลัยเทคโนโลยี</p>
                    <div class="alumni-contact">
                      <a href="mailto:pimjai@email.com" class="contact-btn">
                        <i class="fas fa-envelope"></i>
                      </a>
                      <a href="tel:0845678901" class="contact-btn">
                        <i class="fas fa-phone"></i>
                      </a>
                      <a href="#" class="contact-btn">
                        <i class="fab fa-instagram"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Alumni Card 5 -->
            <div class="col-lg-4 col-md-6 alumni-item" data-year="2564" data-level="ปวส." data-name="นายณัฐพงษ์ สว่างใส">
              <div class="card alumni-card h-100 position-relative">
                <div class="year-badge">2564</div>
                <div class="card-body text-center">
                  <img src="https://bootdey.com/img/Content/avatar/avatar5.png" class="rounded-circle alumni-avatar mx-auto mb-3" alt="Alumni">
                  <div class="alumni-info">
                    <h5>นายณัฐพงษ์ สว่างใส</h5>
                    <p class="text-muted mb-2">ปวส. คอมพิวเตอร์ธุรกิจ</p>
                    <span class="alumni-badge">ทำงานแล้ว</span>
                    <p class="mt-2 mb-2 text-muted small">Software Engineer @ Tech Corp</p>
                    <div class="alumni-contact">
                      <a href="mailto:nuttapong@email.com" class="contact-btn">
                        <i class="fas fa-envelope"></i>
                      </a>
                      <a href="tel:0856789012" class="contact-btn">
                        <i class="fas fa-phone"></i>
                      </a>
                      <a href="#" class="contact-btn">
                        <i class="fab fa-github"></i>
                      </a>
                      <a href="#" class="contact-btn">
                        <i class="fab fa-linkedin"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Alumni Card 6 -->
            <div class="col-lg-4 col-md-6 alumni-item" data-year="2563" data-level="ปวช." data-name="นางสาวกัญญา มีสุข">
              <div class="card alumni-card h-100 position-relative">
                <div class="year-badge">2563</div>
                <div class="card-body text-center">
                  <img src="https://bootdey.com/img/Content/avatar/avatar6.png" class="rounded-circle alumni-avatar mx-auto mb-3" alt="Alumni">
                  <div class="alumni-info">
                    <h5>นางสาวกัญญา มีสุข</h5>
                    <p class="text-muted mb-2">ปวช. คอมพิวเตอร์ธุรกิจ</p>
                    <span class="alumni-badge">ประกอบธุรกิจ</span>
                    <p class="mt-2 mb-2 text-muted small">เจ้าของร้าน Online Shop</p>
                    <div class="alumni-contact">
                      <a href="mailto:kanya@email.com" class="contact-btn">
                        <i class="fas fa-envelope"></i>
                      </a>
                      <a href="tel:0867890123" class="contact-btn">
                        <i class="fas fa-phone"></i>
                      </a>
                      <a href="#" class="contact-btn">
                        <i class="fab fa-facebook"></i>
                      </a>
                      <a href="#" class="contact-btn">
                        <i class="fab fa-instagram"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div class="d-flex justify-content-center mt-5">
            <nav aria-label="Alumni pagination">
              <ul class="pagination">
                <li class="page-item disabled">
                  <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                </li>
                <li class="page-item"><a class="page-link" href="javascript:void(0)">1</a></li>
                <li class="page-item active"><span class="page-link">2</span></li>
                <li class="page-item"><a class="page-link" href="javascript:void(0)">3</a></li>
                <li class="page-item"><a class="page-link" href="javascript:void(0)">4</a></li>
                <li class="page-item">
                  <a class="page-link" href="javascript:void(0)"><i class="fas fa-chevron-right"></i></a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>


  <?php include '../../includes/footer.php' ?>

  <script src="../../assets/js/bootstrap.bundle.min.js"></script>


</body>

</html>