<?php
require_once '../../auth/auth_all.php';
require_once '../../classes/user.php';
require_once '../../classes/webboard.php';
require_once '../../config/function.php';

$db = new Database();
$conn = $db->connect();
$alumni = new User($conn);
$webboard = new Webboard($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    //รายละเอียดโปรไฟล์
    $id = $_GET['id'];

    if(!$user_detail = $alumni->getUser($id)){
        echo "<script>alert('ไม่พบข้อมูลสมาชิก')</script>";
        echo "<script>window.location.href='index.php'</script>";
    }

    $student_code = $user_detail['student_code'];
    $fullname = $user_detail['first_name'] . ' ' . $user_detail['last_name'];
    $image = $user_detail['image'];
    $education_level = $user_detail['education_level'];
    $graduation_year = $user_detail['graduation_year'];
    $user_type = $user_detail['user_type'] == 'alumni' ? 2 : 1;

    $current_job = !empty($user_detail['current_job']) ? $user_detail['current_job'] : 'ไม่มีข้อมูล';
    $current_company = !empty($user_detail['current_company']) ? $user_detail['current_company'] : 'ไม่มีข้อมูล';
    $current_salary = !empty($user_detail['current_salary']) ? $user_detail['current_salary'] : 'ไม่มีข้อมูล';

    $email = $user_detail['email'];
    $phone = $user_detail['phone'];
    $address = !empty($user_detail['address']) ? $user_detail['address'] : "ไม่มีข้อมูล";
    $facebook = $user_detail['facebook'];
    $instagram = $user_detail['instagram'];
    $tiktok = $user_detail['tiktok'];
    $line = $user_detail['line'];

    $status_education = !empty($user_detail['status_education']) ? $user_detail['status_education'] : 'ไม่มีข้อมูล';

    $path_image = '../../assets/images/user/';
    $path_user = ($user_type == USER_TYPE_ALUMNI ? 'alumni' : 'student') . '/' . $user_detail['image'];
    $path = $path_image . $path_user;

    //รายละเอียดกระทู้
    $topic = $webboard->getTopicMe($id, $user_type);
    //print_r($topic);
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

    /* Custom tab styles */
    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 2rem;
    }

    .nav-tabs .nav-link {
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        color: #6c757d;
        font-weight: 500;
        padding: 1rem 1.5rem;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        border-color: transparent;
        color: #467bcb;
        background: rgba(70, 123, 203, 0.05);
    }

    .nav-tabs .nav-link.active {
        color: #467bcb;
        background: rgba(70, 123, 203, 0.05);
        border-color: transparent transparent #467bcb transparent;
        font-weight: 600;
    }

    .tab-content {
        background: transparent;
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
                        <img src="<?php echo !empty($user_detail['image']) ? $path : '../../assets/images/user/no-image-profile.jpg' ?>" class="rounded-circle profile-avatar" alt="<?php echo $fullname ?>">
                    </div>
                    <div class="col-lg-6 col-md-8">
                        <h2 class="mb-2"><?php echo $fullname ?></h2>
                        <h5 class="mb-3 opacity-75">
                            ระดับชั้นที่จบการศึกษา : <?php echo $education_level ?> คอมพิวเตอร์ธุรกิจ | รหัสนักศึกษา: <?php echo $student_code ?>
                        </h5>
                        <div class="status-badge mb-3">
                            <i class="fas fa-graduation-cap me-2"></i>สถานะการศึกษา : <?php echo $status_education ?>
                        </div>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-calendar me-2"></i>ปีที่จบการศึกษา <?php echo $graduation_year ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <nav>
                <div class="nav nav-tabs" id="alumni-tabs" role="tablist">
                    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                        <i class="fas fa-user me-2"></i>โปรไฟล์
                    </button>
                    <button class="nav-link" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button" role="tab" aria-controls="posts" aria-selected="false">
                        <i class="fas fa-newspaper me-2"></i>กระทู้
                    </button>
                </div>
            </nav>

            <!-- Tab Content -->
            <div class="tab-content" id="alumni-tab-content">

                <!-- Profile Tab -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
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
                                        <span class="info-label">ระดับชั้นที่การศึกษา</span>
                                        <span class="info-value"><?php echo $education_level ?></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">ปีที่จบการศึกษา</span>
                                        <span class="info-value"><?php echo $graduation_year ?></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">สถานะการศึกษาต่อ</span>
                                        <span class="info-value"><?php echo $status_education ?></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">ที่อยู่</span>
                                        <span class="info-value"><?php echo $address ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Information -->
                            <div class="detail-card">
                                <div class="detail-card-header">
                                    <i class="fas fa-briefcase me-2"></i>ข้อมูลการทำงาน
                                </div>
                                <div class="detail-card-body">
                                    <div class="info-row">
                                        <span class="info-label">ตำแหน่งงานปัจจุบัน</span>
                                        <span class="info-value"><?php echo $current_job ?></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">ชื่อสถานที่ทำงาน / บริษัท</span>
                                        <span class="info-value"><?php echo $current_company ?></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">เงินเดือน</span>
                                        <span class="info-value"><?php echo $current_salary ?></span>
                                    </div>
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
                                                <i class="fab fa-tiktok"></i>Tiktok
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Posts Tab -->
                <div class="tab-pane fade" id="posts" role="tabpanel" aria-labelledby="posts-tab">
                    <div class="row">
                        <div class="col-12">

                            <!-- Forum List -->
                            <div class="forum-content" id="forumList">
                                <div class="row g-3">

                                    <?php if (!empty($topic)) { ?>
                                        <?php foreach ($topic as $item) {
                                            $id = $item['post_id'];
                                            $user_id = $item['user_id'];
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
                                                                        <label><a class="nav-link" href="profile.php?id=<?php echo $user_id ?>"><?php echo $fullname; ?></a></label>
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

                                </div>

                            </div>

                            <!-- Empty State (show when no posts) -->
                             <?php if(empty($topic)){ ?>
                            <div class="detail-card">
                                <div class="empty-state d-flex flex-column align-items-center p-3">
                                    <i class="fas fa-newspaper" style="font-size: 5rem"></i>
                                    <h5>ยังไม่มีกระทู้</h5>
                                    <p class="text-muted">ผู้ใช้ท่านนี้ยังไม่ได้โพสต์กระทู้ใดๆ</p>
                                </div>
                            </div>
                            <?php } ?>

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