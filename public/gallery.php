<?php
require_once '../config/config.php';
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <?php include '../includes/title.php' ?>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
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
                <div class="col-sm-6 col-md-4 col-lg-3 item">
                    <div class="gallery-item" data-bs-toggle="modal" data-bs-target="#imageModal1">
                        <img class="img-fluid" src="https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU" alt="Image 1">
                        
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 item">
                    <div class="gallery-item" data-bs-toggle="modal" data-bs-target="#imageModal2">
                        <img class="img-fluid" src="https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU" alt="Image 2">
                        
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 item">
                    <div class="gallery-item" data-bs-toggle="modal" data-bs-target="#imageModal3">
                        <img class="img-fluid" src="https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU" alt="Image 3">
                        
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 item">
                    <div class="gallery-item" data-bs-toggle="modal" data-bs-target="#imageModal4">
                        <img class="img-fluid" src="https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU" alt="Image 4">
                        
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 item">
                    <div class="gallery-item" data-bs-toggle="modal" data-bs-target="#imageModal5">
                        <img class="img-fluid" src="https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU" alt="Image 5">
                        
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 item">
                    <div class="gallery-item" data-bs-toggle="modal" data-bs-target="#imageModal6">
                        <img class="img-fluid" src="https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU" alt="Image 6">
                       
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 item">
                    <div class="gallery-item" data-bs-toggle="modal" data-bs-target="#imageModal7">
                        <img class="img-fluid" src="https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU" alt="Image 7">
                        
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 item">
                    <div class="gallery-item" data-bs-toggle="modal" data-bs-target="#imageModal8">
                        <img class="img-fluid" src="https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU" alt="Image 8">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="imageModal1" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img class="modal-image" src="https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU" alt="Image 1">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal2" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img class="modal-image" src="https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU" alt="Image 2">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal3" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img class="modal-image" src="https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU" alt="Image 3">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal4" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img class="modal-image" src="https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU" alt="Image 4">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal5" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img class="modal-image" src="https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU" alt="Image 5">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal6" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img class="modal-image" src="https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU" alt="Image 6">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal7" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img class="modal-image" src="https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU" alt="Image 7">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal8" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img class="modal-image" src="https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU" alt="Image 8">
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php' ?>

    <script src="../assets/js/bootstrap.min.js"></script>
    <script>
        // Array of images
        const images = [
            {
                src: 'https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU',
                alt: 'Image 1'
            },
            {
                src: 'https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU',
                alt: 'Image 2'
            },
            {
                src: 'https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU',
                alt: 'Image 3'
            },
            {
                src: 'https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU',
                alt: 'Image 4'
            },
            {
                src: 'https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU',
                alt: 'Image 5'
            },
            {
                src: 'https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU',
                alt: 'Image 6'
            },
            {
                src: 'https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU',
                alt: 'Image 7'
            },
            {
                src: 'https://artgallery.yale.edu/sites/default/files/styles/hero_small/public/2023-01/ag-doc-2281-0036-pub.jpg?h=147a4df9&itok=xOjI1bjU',
                alt: 'Image 8'
            }
        ];

        let currentImageIndex = 0;

        function openModal(index) {
            currentImageIndex = index;
            updateModalImage();
        }

        function updateModalImage() {
            const modalImage = document.getElementById('modalImage');
            const imageCounter = document.getElementById('imageCounter');
            
            modalImage.src = images[currentImageIndex].src;
            modalImage.alt = images[currentImageIndex].alt;
            imageCounter.textContent = `${currentImageIndex + 1} / ${images.length}`;
        }

        function nextImage() {
            currentImageIndex = (currentImageIndex + 1) % images.length;
            updateModalImage();
        }

        function previousImage() {
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            updateModalImage();
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('imageModal');
            const isModalOpen = modal.classList.contains('show');
            
            if (isModalOpen) {
                if (e.key === 'ArrowRight') {
                    nextImage();
                } else if (e.key === 'ArrowLeft') {
                    previousImage();
                } else if (e.key === 'Escape') {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    modalInstance.hide();
                }
            }
        });

        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        document.getElementById('modalImage').addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.getElementById('modalImage').addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
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