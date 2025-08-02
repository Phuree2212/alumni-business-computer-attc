<?php
require_once '../../auth/auth_user.php';
require_once '../../classes/webboard.php';
require_once '../../classes/pagination_helper.php';
require_once '../../config/function.php';

$db = new Database();
$conn = $db->connect();
$webboard = new Webboard($conn);

// ตั้งค่าพื้นฐาน
$currentPage   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage  = 10;

// รับค่าการค้นหา
$keyword    = trim($_GET['keyword'] ?? '');
$start_date = $_GET['start_date'] ?? '';
$end_date   = $_GET['end_date'] ?? '';
$year_group   = $_GET['year_group'] ?? 0;

// ถ้ามีการกรองข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'GET' && (!empty($keyword) || !empty($start_date) || !empty($end_date) || !empty($year_group))) {

  // นับจำนวนรายการที่ตรงกับเงื่อนไขการค้นหา
  $totalItems = $webboard->getSearchAndFilterCount($keyword, $start_date, $end_date, $year_group);

  // สร้าง pagination
  $pagination = new PaginationHelper($currentPage, $itemsPerPage, $totalItems);

  // ดึงข่าวตามเงื่อนไข
  $topic_posts = $webboard->searchAndFilterForum(
    $keyword,
    $start_date,
    $end_date,
    $year_group,
    $pagination->getLimit(),
    $pagination->getOffset()
  );
} else {
  // นับจำนวนรายการทั้งหมด
  $totalItems = $webboard->getSearchAndFilterCount($keyword, $start_date, $end_date);

  // สร้าง pagination
  $pagination = new PaginationHelper($currentPage, $itemsPerPage, $totalItems);

  // ดึงกระทู้ที่เป็นสาธารณะ
  $topic_posts = $webboard->searchAndFilterForum(
    $keyword,
    $start_date,
    $end_date,
    $year_group,
    $pagination->getLimit(),
    $pagination->getOffset()
  );
}

if(isset($_GET['forum_me']) && $_GET['forum_me'] == 'true'){
   // นับจำนวนรายการทั้งหมด
  $totalItems = $webboard->countTopicMe($_SESSION['user']['id'], $_SESSION['user']['user_type']);

  // สร้าง pagination
  $pagination = new PaginationHelper($currentPage, $itemsPerPage, $totalItems);

  // ดึงกระทู้ที่เป็นสาธารณะ
  $topic_posts = $webboard->getTopicMe(
    $_SESSION['user']['id'],
    $_SESSION['user']['user_type'],
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
  <link rel="stylesheet" href="../../assets/css/sweetalert2.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
  <!-- TinyMCE Editor -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
</head>

<style>
  .sidebar-nav {
    background-color: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1);
    padding: 1.5rem;
  }

  .main-content {
    background-color: #f8f9fa;

  }

  .forum-header {
    background-color: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1);
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
  }

  .forum-card {
    background-color: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1);
    transition: transform 0.2s, box-shadow 0.2s;
    border: none;
  }

  .forum-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px 0 rgba(0, 0, 0, .15);
  }

  .nav-pills .nav-link.active {
    background-color: #467bcb;
    color: #fff;
  }

  .nav-pills .nav-link {
    color: #4a5568;
    border-radius: 0.375rem;
    margin-bottom: 0.5rem;
  }

  .nav-pills .nav-link:hover {
    background-color: #e2e8f0;
  }

  .forum-avatar {
    width: 50px;
    height: 50px;
    object-fit: cover;
  }

  .forum-stats {
    display: flex;
    gap: 1rem;
    align-items: center;
    font-size: 0.875rem;
    color: #6b7280;
  }

  .forum-title {
    color: #1f2937;
    text-decoration: none;
    font-weight: 600;
  }

  .forum-title:hover {
    color: #467bcb;
    text-decoration: none;
  }

  .forum-description {
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.5;
  }

  .forum-meta {
    color: #9ca3af;
    font-size: 0.8125rem;
  }

  .btn-new-discussion {
    background: linear-gradient(135deg, #467bcb 0%, #5a8dd8 100%);
    border: none;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    box-shadow: 0 2px 4px rgba(70, 123, 203, 0.3);
  }

  .btn-new-discussion:hover {
    background: linear-gradient(135deg, #3a6bb0 0%, #4d7dc5 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(70, 123, 203, 0.4);
  }


  /* Accordion styles for responsive sidebar */
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

  .accordion-button::after {
    filter: brightness(0) saturate(100%) invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);
  }

  .accordion-button:not(.collapsed)::after {
    filter: brightness(0) saturate(100%) invert(100%);
  }

  .accordion-body .nav-link {
    padding: 0.5rem 0.75rem;
    margin-bottom: 0.25rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
  }

  @media (max-width: 991.98px) {
    .accordion-item {
      border-radius: 0.5rem;
      overflow: hidden;
      margin-bottom: 0.5rem;
    }

    .accordion-item:last-child {
      margin-bottom: 0;
    }
  }

  .pagination-custom .page-link {
    border: none;
    color: #467bcb;
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
    border-radius: 0.375rem;
  }

  .pagination-custom .page-item.active .page-link {
    background-color: #467bcb;
    color: #fff;
  }

  .pagination-custom .page-link:hover {
    background-color: #e2e8f0;
    color: #467bcb;
  }

  /* ปรับให้ modal อยู่ตรงกลางหน้าจอ */
  .modal-dialog-centered {
    display: flex;
    align-items: center;
    min-height: calc(100% - 1rem);
  }

  /* เพิ่มการตอบสนองสำหรับหน้าจอขนาดเล็ก */
  @media (max-width: 576px) {
    .modal-dialog {
      margin: 0.5rem;
      max-width: calc(100% - 1rem);
    }
  }

  /* ปรับแต่งปุ่มอัพโหลดไฟล์ */
  .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
  }

  /* เพิ่มเอฟเฟกต์เมื่อ modal เปิด */
  .modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
    transform: scale(0.8);
  }

  .modal.show .modal-dialog {
    transform: scale(1);
  }
</style>

<body>
  <?php include '../../includes/navbar.php' ?>

  <div class="container-xxl py-4">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-lg-3 col-md-4">
        <!-- Desktop Sidebar -->
        <div class="mb-3 d-lg-block">
          <div class="sidebar-nav">
            <!-- New Discussion Button -->
            <button class="btn btn-primary btn-new-discussion w-100 mb-4" type="button" data-bs-toggle="modal" data-bs-target="#threadModal">
              <i class="fas fa-plus me-2"></i>
              เพิ่มกระทู้ใหม่่
            </button>

            <!-- Navigation Menu -->
            <nav class="nav nav-pills flex-column">
              <a href="index.php" class="nav-link">
                <i class="fas fa-list me-2"></i>รวมกระทู้สาธารณะทั้งหมด
              </a>
              <?php if($_SESSION['user']['user_type'] == USER_TYPE_ALUMNI){ ?>
                <a href="index.php?year_group=<?php echo $_SESSION['user']['graduation_year'] ?>" class="nav-link">
                  <i class="fas fa-fire me-2"></i>กระทู้ในรุ่นปีการศึกษา <?php echo $_SESSION['user']['graduation_year'] ?>
                </a>
              <?php } ?>
              <a href="index.php?forum_me=true" class="nav-link">
                <i class="fas fa-list me-2"></i>กระทู้ของฉัน
              </a>
            </nav>
          </div>
        </div>

      </div>

      <!-- Main Content -->
      <div class="col-lg-9 col-md-8">
        <div class="main-content">
          <!-- Header Controls -->
          <div class="forum-header">
            <div class="row align-items-center">
              <div class="col-12">
                <form action="index.php" method="GET">
                  <div class="d-flex gap-2 justify-content-md-end">
                    <input type="hidden" name="year_group" value="<?php echo !empty($year_group) ? $year_group : 0 ?>">
                    <input type="text" value="<?php echo !empty($keyword) ? $keyword : '' ?>" name="keyword" class="form-control form-control-sm" style="max-width: 250px;" placeholder="ค้นหากระทู้ เนื้อหา หัวข้อ..." />
                    <button type="submit" class="btn btn-primary">ค้นหา</button>
                    <a href="index.php" class="btn btn-secondary">รีเซ็ต</a>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Forum List -->
          <div class="forum-content" id="forumList">
            <div class="row g-3">

              <?php if (!empty($topic_posts)) { ?>
                <?php foreach ($topic_posts as $item) {
                  $id = $item['post_id'];
                  $fullname = $item['first_name'] . ' ' . $item['last_name'];
                  $path_image = '../../assets/images/user/';
                  $path_user = ($item['user_type'] == USER_TYPE_ALUMNI ? 'alumni' : 'student') . '/' . $item['profile'];
                  $path = $path_image . $path_user;

                  $like_count = $item['like_count'];
                  $comment_count = $item['comment_count'];

                  $title = $item['title'];
                  $content = $item['content'];

                  $created_at = $item['created_at'];
                  $time_only = date("H:i", strtotime($created_at));

                  $thai_format = thaiDateFormat($created_at) . ' ' . $time_only;


                ?>


                  <div class="col-12">
                    <div class="card forum-card">
                      <div class="card-body">
                        <div class="row align-items-center">
                          <div class="col-auto">
                            <div class="d-flex">
                              <div class="avatar me-3">
                                <img src="<?php echo !empty($item['profile']) ? $path : '../../assets/images/user/no-image-profile.jpg' ?>" class="rounded-circle forum-avatar" alt="User" />
                              </div>
                              <div class="d-flex flex-column media-body">
                                <label><?php echo $fullname; ?></label>
                                <span style="font-size: 0.875rem; color: #6c757d;">ประเภทผู้ใช้งาน : <?php echo $item['user_type'] == USER_TYPE_ALUMNI ? 'ศิษย์เก่า' : 'นักเรียน นักศึกษา' ?></span>
                              </div>
                            </div>

                          </div>
                          <div class="mt-3 col-12">
                            <h6 class="">
                              <a href="topic.php?id=<?php echo $id ?>" class="forum-title"><?php echo $title ?></a>
                            </h6>
                            <p class="forum-description mb-2">
                              <?php echo $content ?>
                            </p>
                            <div class="forum-meta">
                              <a href="javascript:void(0)" class="text-decoration-none">ตั้งกระทู้เมื่อ</a>
                              <span class="fw-bold"><?php echo $thai_format ?></span>
                            </div>
                          </div>
                          <div class="col-auto">
                            <div class="forum-stats">
                              <span><i class="far fa-thumbs-up"></i> <?php echo $like_count ?></span>
                              <span><i class="far fa-comment"></i> <?php echo $comment_count ?></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

              <?php }
              } ?>

              <?php if (empty($topic_posts)) { ?>
                <div class="text-center text-danger py-4">ไม่พบข้อมูลกระทู้</div>
              <?php } ?>

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

    <!-- New Thread Modal - ตรงกลางหน้าจอ -->
    <div class="modal fade" id="threadModal" tabindex="-1" aria-labelledby="threadModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <form id="createTopic">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="threadModalLabel">
                <i class="fas fa-plus me-2"></i>สร้างกระทู้ใหม่
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="threadTitle" class="form-label">หัวข้อ / หัวเรื่อง</label>
                <input type="text" name="title" class="form-control" id="threadTitle" placeholder="หัวข้อของกระทู้" required autofocus />
                <div class="invalid-feedback"></div>
              </div>
              <div class="mb-3">
                <label for="threadContent" class="form-label">เนื้อหา</label>
                <textarea class="form-control" name="content" id="content-editor" rows="6" placeholder="เขียนเนื้อหาการสนทนาของคุณที่นี่..." required></textarea>
                <div class="is-invalid text-danger" id="invalidContent"></div>
              </div>
              <div class="mb-3">
                <label for="threadContent" class="form-label">ประเภทการมองเห็นกระทู้</label>
                <select class="form-select" name="group_type" id="selectType">
                  <option value="public">สาธารณะ</option>
                  <option value="year_group">รุ่นของฉัน</option>
                </select>
                <div class="form-text">*สาธารณะ : ทุกคนจะสามารถมองเห็นกระทู้ของท่านได้หมด</div>
                <div class="form-text">*รุ่นของฉัน : ผู้ที่จบปีการศึกษาปีเดียวกับท่าน หรือเพื่อนร่วมรุ่นจึงจะสามารถเห็นกระทู้นี้ได้</div>
              </div>
              <div class="mb-3" style="max-width: 300px;">
                <label for="customFile" class="form-label">แนบไฟล์ภาพ</label>
                <input type="file" accept="image/jpeg,image/png,image/gif,image/webp" name="images[]" class="form-control form-control-sm" id="imageFile" multiple />
                <div class="form-text">รองรับไฟล์: JPEG, PNG, GIF, WebP (ขนาดไม่เกิน 5MB ต่อไฟล์, สูงสุด 5 ไฟล์)</div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">ยกเลิก</button>
              <button type="button" onclick="createTopic()" class="btn btn-primary">
                <i class="fas fa-paper-plane me-2"></i>สร้าง
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>


  <?php include '../../includes/footer.php' ?>

  <script src="../../assets/js/bootstrap.min.js"></script>
  <script src="../../assets/js/function/create_data.js"></script>
  <script src="../function/validate_form.js"></script>
  <script src="../../assets/js/sweetalert2.all.min.js"></script>
  <script src="../../assets/alerts/modal.js"></script>

  <script>
    // Initialize TinyMCE Editor
    tinymce.init({
      selector: '#content-editor',
      height: 400,
      language: 'th',
      plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
      ],
      toolbar: 'undo redo | blocks | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
      content_style: 'body { font-family: Sarabun, Arial, sans-serif; font-size: 14px; }',
      menubar: false,
      branding: false,
      elementpath: false,
      setup: function(editor) {
        editor.on('change', function() {
          editor.save();
        });
      }
    });

    function createTopic() {
      if (!checkvalidFormCreateTopic()) {
        return;
      }

      const form = document.getElementById('createTopic');
      const formData = new FormData(form);

      modalConfirm('ยืนยันการสร้างกระทู้ใหม่', 'ยืนยันการสร้างกระทู้ใหม่')
        .then((result) => {
          if (result.isConfirmed) {
            fetch('create.php', {
                method: 'POST',
                body: formData
              })
              .then(response => response.json())
              .then(response => {
                if (response.result === true) {
                  modalAlert('สร้างกระทู้ใหม่สำเร็จ', 'สร้างกระทู้ใหม่สำเร็จ', 'success')
                    .then(() => location.reload());
                } else {
                  modalAlert('สร้างกระทู้ไม่สำเร็จ เกิดข้อผิดำลาดขึ้น', response.message, 'error');
                }
              })
              .catch(error => {
                modalAlert('การเชื่อมต่อล้มเหลว', 'ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้', 'error');
                console.error('Fetch error:', error);
              });
          }
        });
    }

    function checkvalidFormCreateTopic() {
      const inputTitle = document.getElementById('threadTitle');
      const inputContent = tinymce.get('content-editor').getContent();

      const showErrorContent = document.getElementById('invalidContent');

      console.log(showErrorContent);

      let isValid = true;

      if (!inputTitle.value) {
        showFieldError('threadTitle', 'กรุณากรอกหัวข้อเรื่อง');
        isValid = false;
      }
      if (!inputContent.trim()) {
        showErrorContent.textContent = 'กรุณากรอกข้อมูลเนื้อหา';
        isValid = false;
      }

      return isValid;
    }
  </script>
</body>

</html>