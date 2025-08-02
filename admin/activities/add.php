<?php
require_once '../../auth/auth_admin.php';
require_once '../../classes/activities.php';
require_once '../../classes/image_uploader.php';

$db = new Database();
$conn = $db->connect();

$activity = new Activities($conn);

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <?php include '../../includes/title.php'; ?>
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
</style>

<body>

    <?php include '../includes/sidebar.php' ?>

    <div class="main-content">
        <div class="card-body">
            <h3 class="h3 mb-4">สร้างข้อมูลกิจกรรมใหม่</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">หัวเรื่อง<span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">เนื้อหา<span class="text-danger">*</span></label>
                    <textarea class="form-control" id="content-editor" name="content" rows="3" required></textarea>
                </div>
                <div class="mb-3 w-50">
                    <label class="form-label">วันที่ทำกิจกรรม<span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="formFile" class="form-label">เลือกรูปภาพ<span class="text-danger">*</span></label>
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
                <div class="mb-3">
                    <button type="submit" name="save_activity" class="btn btn-success">บันทึกข้อมูลข่าวสาร</button>
                    <a href="index.php" class="btn btn-danger">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>



    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="../../assets/js/sweetalert2.all.min.js"></script>
    <script src="../../assets/js/script_admin.js"></script>
    <script src="../../assets/alerts/modal.js"></script>
    <script src="../functions/preview_image.js"></script>
    <script src="../functions/tinymce.js"></script>
</body>
<?php
// ตรวจสอบการ submit ฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_activity'])) {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $date = $_POST['date'] ?? '';
    $created_by = 1;

    //อัปโหลดรูปภาพ
    $uploader = new ImageUploader('../../assets/images/activity');
    $uploader->setMaxFileSize(5 * 1024 * 1024) // MAX SIZE 5MB
        ->setMaxFiles(5)
        ->enableThumbnail(300, 300);

    $image_files_name = '';
    if (isset($_FILES['images'])) {
        $result = $uploader->uploadMultiple($_FILES['images'], 'activity');

        if ($result['success']) {
            foreach ($result['files'] as $file) {
                $image_files_name .= $file['fileName'] . ',';
            }
            $image_files_name = rtrim($image_files_name, ',');
        }

        if (!empty($result['errors'])) {
            foreach ($result['errors'] as $error) {
                echo "<script>alert('" . $error . "')</script>";
            }
        }
    }

    // บันทึกข้อมูล
    if ($activity->createActivity($title, $content, $date, $image_files_name, $created_by)) {
        // ส่งกลับหน้ารายการข่าว
        echo "<script>
            modalConfirm('ยืนยันการบันทึกข้อมูล', 'คุณต้องการบันทึกข้อมูลใช่หรือไม่?')
                .then((result) => {
                    if(result.isConfirmed){
                        modalAlert('บันทึกข้อมูลสำเร็จ', '', 'success')
                        .then((result) => {
                            if (result.isConfirmed) {
                                window.location.href='index.php';
                            }
                        });  
                    }
                })
            </script>";
    }

    exit;
}
?>

</html>