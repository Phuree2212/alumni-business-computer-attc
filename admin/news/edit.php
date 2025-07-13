<?php
require_once '../../auth/auth_admin.php';
require_once '../../classes/news.php';
require_once '../../classes/image_uploader.php';

$db = new Database();
$conn = $db->connect();

$news = new News($conn);

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])){
    $id = $_GET['id'];

    //ดึงรายละเอียดข้อมูลข่าวสาร
    $item = $news->getNews($id);
    if($item){
        $title = $item['title'];
        $content = $item['content'];
        $images = $item['image'];
        $existing_images = !empty($images) ? explode(',', $images) : [];
    }else{
        echo "<script>alert('ไม่พบข้อมูลข่าวสาร')</script>";
        echo "<script>window.location.href='index.php'</script>";
    }
    
}

//ถ้าไม่มีการค่าไอดีส่งมา และไม่มีการกดปุ่มอัพเดตขอมูลให้กลับไปที่หน้าแรก
if(!isset($_GET['id']) && !isset($_POST['update_news'])){
    header('Location: index.php');
}

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../../assets/css/style_admin.css" rel="stylesheet">
    <link href="../../assets/css/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- TinyMCE Editor -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
</head>
<style>
    .card-body {
        -webkit-box-flex: 1;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        padding: 1.5rem 1.5rem;
    }

    .image-preview {
        max-width: 200px;
        max-height: 200px;
        margin-top: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
    }

    .preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .preview-item {
        position: relative;
        display: inline-block;
    }

    .preview-item img {
        max-width: 150px;
        max-height: 150px;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
    }

    .existing-image-container {
        position: relative;
        display: inline-block;
        margin: 5px;
    }

    .existing-image-container img {
        max-width: 150px;
        max-height: 150px;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
    }

    .delete-image-btn {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        cursor: pointer;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .delete-image-btn:hover {
        background: #c82333;
    }

    .image-section {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }

    .section-title {
        font-weight: bold;
        margin-bottom: 10px;
        color: #495057;
    }
</style>

<body>

    <?php include '../includes/sidebar.php' ?>

    <div class="main-content">
        <div class="card-body">
            <h3 class="h3 mb-4">แก้ไขข้อมูลข่าวสาร</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="news_id" value="<?php echo $id ?>">
                <input type="hidden" name="deleted_images" id="deletedImages" value="">
                
                <div class="mb-3">
                    <label class="form-label">หัวเรื่อง<span class="text-danger">*</span></label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($title ?? '') ?>" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">เนื้อหา<span class="text-danger">*</span></label>
                    <textarea class="form-control" id="content-editor" name="content" rows="3" required><?php echo htmlspecialchars($content ?? '') ?></textarea>
                </div>

                <!-- แสดงรูปภาพเดิม -->
                <?php if (!empty($existing_images)): ?>
                <div class="image-section">
                    <div class="section-title">รูปภาพปัจจุบัน</div>
                    <div id="existingImagesContainer" class="preview-container">
                        <?php foreach ($existing_images as $index => $image): ?>
                            <?php if (!empty(trim($image))): ?>
                                <div class="existing-image-container" data-image="<?php echo htmlspecialchars($image) ?>">
                                    <img src="../../assets/images/news/<?php echo htmlspecialchars($image) ?>" 
                                         alt="Existing image <?php echo $index + 1 ?>"
                                         onerror="this.src='../../assets/images/no-image.png'">
                                    <button type="button" class="delete-image-btn" onclick="removeExistingImage(this, '<?php echo htmlspecialchars($image) ?>')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <div class="text-center small mt-1"><?php echo htmlspecialchars($image) ?></div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- เพิ่มรูปภาพใหม่ -->
                <div class="image-section">
                    <div class="section-title">เพิ่มรูปภาพใหม่</div>
                    <div class="mb-3">
                        <label for="formFile" class="form-label">เลือกรูปภาพ</label>
                        <input class="form-control"
                            name="images[]"
                            type="file"
                            id="formFile"
                            accept="image/jpeg,image/png,image/gif,image/webp"
                            multiple
                            onchange="previewImages(this)">
                        <div class="form-text">รองรับไฟล์: JPEG, PNG, GIF, WebP (ขนาดไม่เกิน 5MB ต่อไฟล์, สูงสุด 5 ไฟล์)</div>
                        <div id="imagePreviewContainer" class="preview-container"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" name="update_news" class="btn btn-success">บันทึกการแก้ไข</button>
                    <a href="index.php" class="btn btn-danger">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>

    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="../../assets/js/sweetalert2.all.min.js"></script>
    <script src="../../assets/js/script_admin.js"></script>
    <script src="../../assets/alerts/modal.js"></script>
    <script src="../functions/remove_image.js"></script>
    <script src="../functions/preview_image.js"></script>
    <script src="../functions/tinymce.js"></script>
</body>

<?php
// ตรวจสอบการ submit ฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_news'])) {
    $news_id = $_POST['news_id'] ?? '';
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $deleted_images = $_POST['deleted_images'] ?? '';

    // Get current images
    $current_item = $news->getNews($news_id);
    $current_images = !empty($current_item['image']) ? explode(',', $current_item['image']) : [];

    // Remove deleted images from current images array
    if (!empty($deleted_images)) {
        $deleted_array = explode(',', $deleted_images);
        $current_images = array_diff($current_images, $deleted_array);
        
        // Delete physical files using ImageUploader's deleteFile method
        $uploader = new ImageUploader('../../assets/images/news');
        foreach ($deleted_array as $deleted_image) {
            $uploader->deleteFile($deleted_image);
        }
    }

    // Handle new image uploads
    $new_image_files = '';
    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        $uploader = new ImageUploader('../../assets/images/news');
        $uploader->setMaxFileSize(5 * 1024 * 1024) // MAX SIZE 5MB
            ->setMaxFiles(5 - count($current_images)) // Limit based on existing images
            ->enableThumbnail(300, 300);

        $result = $uploader->uploadMultiple($_FILES['images'], 'news');

        if ($result['success']) {
            foreach ($result['files'] as $file) {
                $new_image_files .= $file['fileName'] . ',';
            }
            $new_image_files = rtrim($new_image_files, ',');
        }

        if (!empty($result['errors'])) {
            foreach ($result['errors'] as $error) {
                echo "<script>alert('" . $error . "')</script>";
            }
        }
    }

    // Combine existing and new images
    $final_images = array_merge($current_images, explode(',', $new_image_files));
    $final_images = array_filter($final_images); // Remove empty values
    $final_images_string = implode(',', $final_images);

    // Update news data
    if ($news->editNews($news_id, $title, $content, $final_images_string)) {
        echo "<script> modalConfirm('ยืนยันการแก้ไขข้อมูล', 'คุณต้องการแก้ไขข้อมูลใช่หรือไม่?')
                .then((result) => {
                    if(result.isConfirmed){
                        modalAlert('แก้ไขข้อมูลสำเร็จ', '', 'success')
                        .then((result) => {
                            if (result.isConfirmed) {
                                window.location.href='index.php';
                            }
                        });  
                    }
                })</script>";
    } else {
        echo "<script>modalAlert('เกิดข้อผิดพลาด', 'ไม่สามารถแก้ไขข้อมูลได้', 'error');</script>";
    }

    exit;
}
?>

</html>