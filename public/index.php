<?php
require_once '../config/config.php';
require_once '../classes/news.php';
require_once '../classes/activities.php';
require_once '../config/function.php';

$db = new Database();
$conn = $db->connect();
$news = new News($conn);
$activity = new Activities($conn);

$news_list = $news->getAllNews(6, 0);
$activity_list = $activity->getAllActivity(6, 0);

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <?php include '../includes/title.php' ?>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    
    <style>
        .section-title {
            color: var(--primary-blue);
            font-weight: 600;
            margin-bottom: 2rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--secondary-blue);
            border-radius: 2px;
        }

        .card {
            border: 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: var(--secondary-blue);
            border-color: var(--secondary-blue);
        }

        .btn-primary:hover {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .btn-outline-primary {
            color: var(--secondary-blue);
            border-color: var(--secondary-blue);
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
        }

        .btn-outline-primary:hover {
            background: var(--secondary-blue);
            border-color: var(--secondary-blue);
        }

        .activity-img {
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <?php include '../includes/navbar.php'; ?>

    <!-- แทนที่ section ที่มีรูปภาพแนะนำแผนกเดิม -->
    <section>
        <div class="container mt-4">
            <div class="row">
                <div>
                    <!-- Image Carousel -->
                    <div id="departmentCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#departmentCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#departmentCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#departmentCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            <button type="button" data-bs-target="#departmentCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img style="width: 100%; height: 600px; object-fit: cover; border-radius: 10px;"
                                    src="../assets/images/banners/banner_4.jpg"
                                    alt="การแข่งขันทักษะ">

                            </div>
                            <div class="carousel-item">
                                <img style="width: 100%; height: 600px; object-fit: cover; border-radius: 10px;"
                                    src="../assets/images/banners/banner_2.jpg"
                                    alt="สภาพแวดล้อมการเรียนรู้">

                            </div>
                            <div class="carousel-item">
                                <img style="width: 100%; height: 600px; object-fit: cover; border-radius: 10px;"
                                    src="../assets/images/banners/banner_3.jpg"
                                    alt="โครงงานนักเรียน">

                            </div>
                            
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#departmentCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#departmentCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Activities & News -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 mb-4">
                    <h2 class="section-title">เข้าสู่ระบบ</h2>

                    <form class="gap-3">
                        <div class="mb-1">
                            <label for="exampleInputEmail1" class="form-label">ชื่อผู้ใช้/รหัสนักศึกษา</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-1">
                            <label for="exampleInputPassword1" class="form-label">รหัสผ่าน</label>
                            <input type="password" class="form-control" id="exampleInputPassword1">
                        </div>
                        <div class="mb-1 form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">จดจำการเข้าสูระบบ</label>
                        </div>
                        <a href="#" class="btn btn-primary w-100 mb-2">เข้าสู่ระบบ</a>
                        <a href="register.php" class="btn btn-success w-100">ลงทะเบียน</a>
                    </form>

                </div>
                <!-- News -->
                <div class="col-lg-6 mb-4">
                    <h2 class="section-title">ข่าวสาร/ประชาสัมพันธ์</h2>
                    <div class="row g-4">

                        <?php if (!empty($news_list)) {
                            foreach ($news_list as $item) {
                                $id = $item['news_id'];
                                $image = explode(',', $item['image'])[0];
                                $title = $item['title'];
                                $content = strip_tags(mb_substr($item['content'], 0, 200, 'UTF-8') . '...');
                                $created_at = date('d-m-Y', strtotime($item['created_at']));
                        ?>

                                <div class="col-md-6">
                                    <a class="nav-link" href="news/detail.php?id=<?php echo $id ?>">
                                        <div class="card h-100">
                                            <div class="card-img-top" style="height: 250px; overflow: hidden;">
                                                <img src="../assets/images/news/<?php echo $image ?>" alt="" class="w-100 h-100" style="object-fit: cover;">
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo $title ?></h5>
                                                <p class="card-text"><?php echo $content ?></p>
                                                <small class="text-muted"><?php echo thaiDateFormat($created_at) ?></small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                        <?php }
                        } ?>

                    </div>
                    <div class="text-center mt-4">
                        <a href="news/" class="btn btn-primary btn-lg">ดูข่าวสารทั้งหมด</a>
                    </div>
                </div>

                <!-- News -->
                <div class="col-lg-3">
                    <h2 class="section-title">แกลเลอรี</h2>
                    <div class="row gap-3">
                        <div class="col-md-3">
                            <img style="width: 70px; height: 70px;" src="https://www.freeiconspng.com/thumbs/no-image-icon/no-image-icon-6.png" alt="">
                        </div>
                        <div class="col-md-3">
                            <img style="width: 70px; height: 70px;" src="https://www.freeiconspng.com/thumbs/no-image-icon/no-image-icon-6.png" alt="">
                        </div>
                        <div class="col-md-3">
                            <img style="width: 70px; height: 70px;" src="https://www.freeiconspng.com/thumbs/no-image-icon/no-image-icon-6.png" alt="">
                        </div>
                        <div class="col-md-3">
                            <img style="width: 70px; height: 70px;" src="https://www.freeiconspng.com/thumbs/no-image-icon/no-image-icon-6.png" alt="">
                        </div>
                        <div class="col-md-3">
                            <img style="width: 70px; height: 70px;" src="https://www.freeiconspng.com/thumbs/no-image-icon/no-image-icon-6.png" alt="">
                        </div>
                        <div class="col-md-3">
                            <img style="width: 70px; height: 70px;" src="https://www.freeiconspng.com/thumbs/no-image-icon/no-image-icon-6.png" alt="">
                        </div>
                        <div class="col-md-3">
                            <img style="width: 70px; height: 70px;" src="https://www.freeiconspng.com/thumbs/no-image-icon/no-image-icon-6.png" alt="">
                        </div>
                        <div class="col-md-3">
                            <img style="width: 70px; height: 70px;" src="https://www.freeiconspng.com/thumbs/no-image-icon/no-image-icon-6.png" alt="">
                        </div>
                        <div class="col-md-3">
                            <img style="width: 70px; height: 70px;" src="https://www.freeiconspng.com/thumbs/no-image-icon/no-image-icon-6.png" alt="">
                        </div>
                    </div>

                </div>
    </section>

    <!-- Knowledge Articles -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5">กิจกรรม</h2>
            <div class="row g-4">

                <?php if (!empty($activity_list)) {
                    foreach ($activity_list as $item) {
                        $id = $item['activity_id'];
                        $image = explode(',', $item['image'])[0];
                        $title = $item['title'];
                        $content = strip_tags(mb_substr($item['content'], 0, 200, 'UTF-8') . '...');
                        $views_count = isset($item['views_count']) ? $item['views_count'] : 0;
                        $created_at = date('d-m-Y', strtotime($item['created_at']));
                ?>

                        <div class="col-lg-4">
                            <a href="activities/detail.php?id=<?php echo $id ?>" class="nav-link">
                                <div class="card h-100">
                                    <div class="card-img-top" style="height: 250px;">
                                        <img src="../assets/images/activity/<?php echo $image ?>" alt="" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $title ?></h5>
                                        <p class="card-text"><?php echo $content ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">เข้าชม <?php echo $views_count ?> ครั้ง</small>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                <?php }
                } ?>



                <div class="text-center mt-4">
                    <a href="activities/" class="btn btn-primary btn-lg">ดูกิจกรรมทั้งหมด</a>
                </div>
            </div>
    </section>

    <?php include '../includes/footer.php' ?>

    <script src="../assets/js/bootstrap.min.js"></script>
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                navbar.style.backdropFilter = 'blur(10px)';
            } else {
                navbar.style.backgroundColor = 'white';
                navbar.style.backdropFilter = 'none';
            }
        });
    </script>
</body>

</html>