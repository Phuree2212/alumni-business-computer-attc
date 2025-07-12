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

</style>
<body>
    <?php include '../../includes/navbar.php' ?>

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
                
            </div>
    </section>

    <?php include '../../includes/footer.php' ?>

    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>