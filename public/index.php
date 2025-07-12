<?php 
require_once '../config/config.php';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <?php include '../includes/title.php' ?>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet" >
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
                            <img style="width: 100%; height: 500px; object-fit: cover; border-radius: 10px;" 
                                 src="https://scontent.fbkk26-1.fna.fbcdn.net/v/t39.30808-6/473356916_122204398064223852_455465025406390313_n.jpg?_nc_cat=111&ccb=1-7&_nc_sid=833d8c&_nc_ohc=E4cLm0WYfrAQ7kNvwHzydvB&_nc_oc=Adkyz-JFiMoMUF35GbufPcvYfSJNadRtNXtBD07bFcphoZF3-rhMTrljmtK8vmf21_o&_nc_zt=23&_nc_ht=scontent.fbkk26-1.fna&_nc_gid=nhccDndWuI0URBscXuNxqA&oh=00_AfOuCkkUWwYjW0LdaH_o78m60XcHh51A-rfLMVixCTgn-w&oe=68536C66" 
                                 alt="การแข่งขันทักษะ">
                            
                        </div>
                        <div class="carousel-item">
                            <img style="width: 100%; height: 500px; object-fit: cover; border-radius: 10px;" 
                                 src="https://www.attc.ac.th/images/slides/slide4.jpg" 
                                 alt="สภาพแวดล้อมการเรียนรู้">
                            
                        </div>
                        <div class="carousel-item">
                            <img style="width: 100%; height: 500px; object-fit: cover; border-radius: 10px;" 
                                 src="https://scontent.fbkk26-1.fna.fbcdn.net/v/t39.30808-6/495241626_1018946563736731_6616970425267827524_n.jpg?_nc_cat=103&ccb=1-7&_nc_sid=127cfc&_nc_ohc=JNdMncUN_FQQ7kNvwEoMhmA&_nc_oc=AdkdVDfahx1M_JdEhLSbTxNXxlN74oUrGJhsH4zJT4GW6yBQv7jwb8NSmoHZ5fJM67M&_nc_zt=23&_nc_ht=scontent.fbkk26-1.fna&_nc_gid=1rEZ7Xk3lu7vO4BmDhALKQ&oh=00_AfOaUzOwqvAlQbQ8AlTac1SYHfvQejPHIfcceIY4jx2oZA&oe=6850C9A7" 
                                 alt="โครงงานนักเรียน">
                           
                        </div>
                        <div class="carousel-item">
                            <img style="width: 100%; height: 500px; object-fit: cover; border-radius: 10px;" 
                                 src="https://scontent.fbkk26-1.fna.fbcdn.net/v/t39.30808-6/506210739_1042206444744076_5307432618440414826_n.jpg?stp=cp6_dst-jpegr_tt6&_nc_cat=101&ccb=1-7&_nc_sid=127cfc&_nc_ohc=Q1IBOUjp80AQ7kNvwHtf_2q&_nc_oc=AdmniNyHG5OGJf2zfVrr0M0IKORlXxt7mzqqshNX13J0K7nx0pxVstGjUMdkvcDqwn8&_nc_zt=23&se=-1&_nc_ht=scontent.fbkk26-1.fna&_nc_gid=bnuY8ZJKBFXcn9CyNU8rrA&oh=00_AfPlHuAfkejOTbFkGXmtSYCKFPsQUVLKpR79n7GzxOvCRA&oe=6850AC25" 
                                 alt="กิจกรรมวันไหว้ครู">
                           
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
                <!-- Activities -->
                <div class="col-lg-6 mb-4">
                    <h2 class="section-title">ข่าวสาร/ประชาสัมพันธ์</h2>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-img-top" style="height: 250px; overflow: hidden;">
                                    <img src="https://scontent.fbkk26-1.fna.fbcdn.net/v/t39.30808-6/473356916_122204398064223852_455465025406390313_n.jpg?_nc_cat=111&ccb=1-7&_nc_sid=833d8c&_nc_ohc=E4cLm0WYfrAQ7kNvwHzydvB&_nc_oc=Adkyz-JFiMoMUF35GbufPcvYfSJNadRtNXtBD07bFcphoZF3-rhMTrljmtK8vmf21_o&_nc_zt=23&_nc_ht=scontent.fbkk26-1.fna&_nc_gid=nhccDndWuI0URBscXuNxqA&oh=00_AfOuCkkUWwYjW0LdaH_o78m60XcHh51A-rfLMVixCTgn-w&oe=68536C66" alt="การแข่งขันทักษะ Web Development" class="w-100 h-100" style="object-fit: cover;">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">88 ปีคืนสู่เหย้าชาวเทคนิคอ่างทอง</h5>
                                    <p class="card-text">🌈”88 ปี #คืนสู่เหย้าชาวเทคนิคอ่างทอง“***
                                        ***นอกจาก #วท.อ่างทอง มีกิจกรรมงาน #วิ่ง เพื่อการกุศล (88 ปี เทคนิคอ่างทองมินิมาราธอน ครั้งที่ 1)  🏃🏃‍♀️🏃‍♂️ในวันที่ 7 ก.ค. 67 แล้ว</p>
                                    <small class="text-muted">วันที่ 15 พฤษภาคม 2567</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-img-top" style="height: 250px; overflow: hidden;">
                                    <img src="https://scontent.fbkk26-1.fna.fbcdn.net/v/t39.30808-6/482354195_1193826242371806_8188635952895047065_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=833d8c&_nc_ohc=2JiV72pYqPAQ7kNvwG0NYjE&_nc_oc=Adl-kvHVEkRaaVrOcTlOB05G0FaRWk1ZQqpqkzk3iolI7rEtaS-F4Zx-G4DMUv2r5B8&_nc_zt=23&_nc_ht=scontent.fbkk26-1.fna&_nc_gid=lpZZ2GA7733csjKm5M56hg&oh=00_AfNVTYC2ipoDWme1gatERFHT-uJs1MlVmrbRSb7oPpo8Rw&oe=685362E9" alt="การแข่งขันทักษะ Web Development" class="w-100 h-100" style="object-fit: cover;">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">ขอเชิญร่วมสนับสนุน กิจกรรมคณะสีเหลือง</h5>
                                    <p class="card-text">ขอเชิญพี่ๆ ร่วมเป็น Sponsor ให้กับคณะสีเหลือง ปีการศึกษา 2568</p>
                                    <small class="text-muted">วันที่ 15 พฤษภาคม 2567</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-img-top" style="height: 250px; overflow: hidden;">
                                    <img src="https://scontent.fbkk26-1.fna.fbcdn.net/v/t39.30808-6/506829023_1150325077113833_5005733072594993767_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=f727a1&_nc_ohc=sU-MsvfB2twQ7kNvwELM1nc&_nc_oc=AdmJnMseetvuEtvKSoKrogui8b6DHbqdvXJfNik6La3JhZSCfvwzjZEYq50THTmDB8g&_nc_zt=23&_nc_ht=scontent.fbkk26-1.fna&_nc_gid=QV3bc9kIUmHulJdmEhwL4g&oh=00_AfM8rVfJg51DHu2TtHXHSSsZ-gmuYDnOO4C9HlYYyBf-cQ&oe=68536FF8" alt="การแข่งขันทักษะ Web Development" class="w-100 h-100" style="object-fit: cover;">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">ขอเชิญเข้าร่วมกิจกรรมวันไหว้ครู ในวันที่ 12 มิถุนายน 2568</h5>
                                    <p class="card-text">วันไหว้ครู ประจำปีการศึกษา 2568
                                        📍แผนกวิชาคอมพิวเตอร์ธุรกิจ วิทยาลัยเทคนิคอ่างทอง</p>
                                    <small class="text-muted">วันที่ 15 พฤษภาคม 2567</small>
                                </div>
                            </div>
                        </div>
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
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-img-top" style="height: 250px;">
                            <img src="https://scontent.fbkk26-1.fna.fbcdn.net/v/t39.30808-6/503309010_24131670466429904_6720287527956751855_n.jpg?stp=cp6_dst-jpg_p180x540_tt6&_nc_cat=104&ccb=1-7&_nc_sid=833d8c&_nc_ohc=MyTLp92cOqAQ7kNvwGBKxz3&_nc_oc=AdmUwUYs_FV4ZreFLADJFMzuig1psCPMrBoi2AsvNMqnlEB0hGKtDctDWt1CHd-L27U&_nc_zt=23&_nc_ht=scontent.fbkk26-1.fna&_nc_gid=IzC0qhhjFjxw-1H18w7aUg&oh=00_AfO2bjgsqb0YMmsMRCJCRO_KrUCNz5rX-hV0Kp-Zm-_ycg&oe=68537CB7" alt="การแข่งขันทักษะ Web Development" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">88 ปีคืนสู่เหย้าชาวเทคนิคอ่างทอง</h5>
                            <p class="card-text">💙 ๘๘ ปี คืนสู่เหย้า “ชาวเทคนิคอ่างทอง”
                                👉 ปวส. คอมพิวเตอร์ธุรกิจ รุ่นที่ 1 
                                👉 ศิษย์เก่า วท.อ่างทอง รุ่นที่ 59 (2537)</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">เข้าชม 128 ครั้ง</small>
                                <a href="#" class="btn btn-sm btn-outline-info">อ่านต่อ</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-img-top" style="height: 250px;">
                            <img src="https://scontent.fbkk26-1.fna.fbcdn.net/v/t39.30808-6/503309010_24131670466429904_6720287527956751855_n.jpg?stp=cp6_dst-jpg_p180x540_tt6&_nc_cat=104&ccb=1-7&_nc_sid=833d8c&_nc_ohc=MyTLp92cOqAQ7kNvwGBKxz3&_nc_oc=AdmUwUYs_FV4ZreFLADJFMzuig1psCPMrBoi2AsvNMqnlEB0hGKtDctDWt1CHd-L27U&_nc_zt=23&_nc_ht=scontent.fbkk26-1.fna&_nc_gid=IzC0qhhjFjxw-1H18w7aUg&oh=00_AfO2bjgsqb0YMmsMRCJCRO_KrUCNz5rX-hV0Kp-Zm-_ycg&oe=68537CB7" alt="การแข่งขันทักษะ Web Development" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">88 ปีคืนสู่เหย้าชาวเทคนิคอ่างทอง</h5>
                            <p class="card-text">💙 ๘๘ ปี คืนสู่เหย้า “ชาวเทคนิคอ่างทอง”
                                👉 ปวส. คอมพิวเตอร์ธุรกิจ รุ่นที่ 1 
                                👉 ศิษย์เก่า วท.อ่างทอง รุ่นที่ 59 (2537)</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">เข้าชม 128 ครั้ง</small>
                                <a href="#" class="btn btn-sm btn-outline-info">อ่านต่อ</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-img-top" style="height: 250px;">
                            <img src="https://scontent.fbkk26-1.fna.fbcdn.net/v/t39.30808-6/503309010_24131670466429904_6720287527956751855_n.jpg?stp=cp6_dst-jpg_p180x540_tt6&_nc_cat=104&ccb=1-7&_nc_sid=833d8c&_nc_ohc=MyTLp92cOqAQ7kNvwGBKxz3&_nc_oc=AdmUwUYs_FV4ZreFLADJFMzuig1psCPMrBoi2AsvNMqnlEB0hGKtDctDWt1CHd-L27U&_nc_zt=23&_nc_ht=scontent.fbkk26-1.fna&_nc_gid=IzC0qhhjFjxw-1H18w7aUg&oh=00_AfO2bjgsqb0YMmsMRCJCRO_KrUCNz5rX-hV0Kp-Zm-_ycg&oe=68537CB7" alt="การแข่งขันทักษะ Web Development" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">88 ปีคืนสู่เหย้าชาวเทคนิคอ่างทอง</h5>
                            <p class="card-text">💙 ๘๘ ปี คืนสู่เหย้า “ชาวเทคนิคอ่างทอง”
                                👉 ปวส. คอมพิวเตอร์ธุรกิจ รุ่นที่ 1 
                                👉 ศิษย์เก่า วท.อ่างทอง รุ่นที่ 59 (2537)</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">เข้าชม 128 ครั้ง</small>
                                <a href="#" class="btn btn-sm btn-outline-info">อ่านต่อ</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-img-top" style="height: 250px;">
                            <img src="https://scontent.fbkk26-1.fna.fbcdn.net/v/t39.30808-6/503309010_24131670466429904_6720287527956751855_n.jpg?stp=cp6_dst-jpg_p180x540_tt6&_nc_cat=104&ccb=1-7&_nc_sid=833d8c&_nc_ohc=MyTLp92cOqAQ7kNvwGBKxz3&_nc_oc=AdmUwUYs_FV4ZreFLADJFMzuig1psCPMrBoi2AsvNMqnlEB0hGKtDctDWt1CHd-L27U&_nc_zt=23&_nc_ht=scontent.fbkk26-1.fna&_nc_gid=IzC0qhhjFjxw-1H18w7aUg&oh=00_AfO2bjgsqb0YMmsMRCJCRO_KrUCNz5rX-hV0Kp-Zm-_ycg&oe=68537CB7" alt="การแข่งขันทักษะ Web Development" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">88 ปีคืนสู่เหย้าชาวเทคนิคอ่างทอง</h5>
                            <p class="card-text">💙 ๘๘ ปี คืนสู่เหย้า “ชาวเทคนิคอ่างทอง”
                                👉 ปวส. คอมพิวเตอร์ธุรกิจ รุ่นที่ 1 
                                👉 ศิษย์เก่า วท.อ่างทอง รุ่นที่ 59 (2537)</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">เข้าชม 128 ครั้ง</small>
                                <a href="#" class="btn btn-sm btn-outline-info">อ่านต่อ</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                
            
            <div class="text-center mt-4">
                <a href="#" class="btn btn-primary btn-lg">ดูกิจกรรมทั้งหมด</a>
            </div>
        </div>
    </section>
    
    <?php include '../includes/footer.php' ?>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
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