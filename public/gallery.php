<?php
require_once '../config/config.php';
require_once '../classes/news.php';
require_once '../classes/activities.php';
require_once '../classes/gallery.php';
require_once '../classes/visitor_tracker.php';

$db = new Database();
$conn = $db->connect();

$tracker = new VisitorTracker($conn);
$tracker->track();

$news = new News($conn);
$activity = new Activities($conn);

$news_list = $news->getAllNews(6, 0);
$activity_list = $activity->getAllActivity(6, 0);

//ดึงรูปภาพ gallery
$gallery_class = new Gallery($news, $activity);
$galleries = $gallery_class->getAllImages();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <?php include '../includes/title.php' ?>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
    <style>
        .photo-gallery {
            color: #313437;
            background-color: #fff;
        }

        .photo-gallery p {
            color: #7d8285;
        }

        .photo-gallery h2 {
            font-weight: bold;
            margin-bottom: 40px;
            padding-top: 40px;
            color: inherit;
        }

        @media (max-width:767px) {
            .photo-gallery h2 {
                margin-bottom: 25px;
                padding-top: 25px;
                font-size: 24px;
            }
        }

        .photo-gallery .intro {
            font-size: 16px;
            max-width: 500px;
            margin: 0 auto 40px;
        }

        .photo-gallery .intro p {
            margin-bottom: 0;
        }

        .photo-gallery .photos {
            padding-bottom: 20px;
        }

        .photo-gallery .item {
            padding-bottom: 30px;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease;
            border-radius: 8px;
        }

        .gallery-item:hover {
            transform: scale(1.05);
        }

        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .gallery-item .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 8px;
        }

        .gallery-item:hover .overlay {
            opacity: 1;
        }

        .gallery-item .overlay i {
            color: white;
            font-size: 2rem;
        }

        .modal-body {
            padding: 0;
            position: relative;
        }

        .modal-dialog {
            max-width: 90vw;
            margin: 2rem auto;
        }

        .modal-content {
            background: transparent;
            border: none;
        }

        .modal-image {
            width: 100%;
            height: auto;
            max-height: 80vh;
            object-fit: contain;
            border-radius: 8px;
        }

        .modal-title {
            color: white;
        }

        .btn-close {
            filter: invert(1);
        }

        .modal-navigation {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
            z-index: 1060;
        }

        .modal-navigation:hover {
            background: rgba(0, 0, 0, 0.9);
            color: white;
        }

        .btn-prev {
            left: 20px;
        }

        .btn-next {
            right: 20px;
        }

        .image-counter {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            z-index: 1060;
        }
    </style>
</head>

<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="photo-gallery">
        <div class="container">
            <div class="intro">
                <h2 class="text-center">แกลเลอรี/รูปภาพ</h2>
                <h5 class="text-center">รวมรูปภาพกิจกรรมในแผนกวิชาคอมพิวเตอร์ธุรกิจ</h5>
            </div>
            <div class="row photos">
                <?php if (!empty($galleries)) {
                    $index = 0;
                    foreach ($galleries as $gallery) {
                        $image_path = "../assets/images/{$gallery['type']}/{$gallery['image']}";
                ?>
                        <div class="col-sm-6 col-md-4 col-lg-3 item">
                            <div class="gallery-item" onclick="openModal(<?php echo $index; ?>)">
                                <img class="img-fluid" src="<?php echo $image_path ?>">
                            </div>
                        </div>
                <?php 
                        $index++;
                    }
                } ?>
            </div>
        </div>
    </div>

    <!-- Single Modal for all images -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img class="modal-image" id="modalImage" src="" alt="">
                    <button class="modal-navigation btn-prev" onclick="previousImage()">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <button class="modal-navigation btn-next" onclick="nextImage()">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    <div class="image-counter" id="imageCounter">1 / 1</div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php' ?>

    <script src="../assets/js/bootstrap.min.js"></script>
    <script>
        // Create images array from PHP data
        const images = [
            <?php 
            if (!empty($galleries)) {
                $js_images = [];
                foreach ($galleries as $gallery) {
                    $image_path = "../assets/images/{$gallery['type']}/{$gallery['image']}";
                    $js_images[] = "{ src: '" . addslashes($image_path) . "', alt: 'Gallery Image' }";
                }
                echo implode(',', $js_images);
            }
            ?>
        ];

        let currentImageIndex = 0;
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));

        function openModal(index) {
            currentImageIndex = index;
            updateModalImage();
            modal.show();
        }

        function updateModalImage() {
            const modalImage = document.getElementById('modalImage');
            const imageCounter = document.getElementById('imageCounter');

            if (images.length > 0) {
                modalImage.src = images[currentImageIndex].src;
                modalImage.alt = images[currentImageIndex].alt;
                imageCounter.textContent = `${currentImageIndex + 1} / ${images.length}`;
                
                // Show/hide navigation buttons
                const prevBtn = document.querySelector('.btn-prev');
                const nextBtn = document.querySelector('.btn-next');
                
                if (images.length <= 1) {
                    prevBtn.style.display = 'none';
                    nextBtn.style.display = 'none';
                } else {
                    prevBtn.style.display = 'flex';
                    nextBtn.style.display = 'flex';
                }
            }
        }

        function nextImage() {
            if (images.length > 1) {
                currentImageIndex = (currentImageIndex + 1) % images.length;
                updateModalImage();
            }
        }

        function previousImage() {
            if (images.length > 1) {
                currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
                updateModalImage();
            }
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const modalElement = document.getElementById('imageModal');
            const isModalOpen = modalElement.classList.contains('show');

            if (isModalOpen) {
                if (e.key === 'ArrowRight') {
                    nextImage();
                } else if (e.key === 'ArrowLeft') {
                    previousImage();
                } else if (e.key === 'Escape') {
                    modal.hide();
                }
            }
        });

        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('DOMContentLoaded', function() {
            const modalImage = document.getElementById('modalImage');
            
            modalImage.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            });

            modalImage.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    nextImage(); // Swipe left - next image
                } else {
                    previousImage(); // Swipe right - previous image
                }
            }
        }
    </script>
</body>

</html>