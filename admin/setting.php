<?php
require_once '../auth/auth_admin.php';
require_once '../classes/setting.php';
require_once '../classes/image_uploader.php';

$conn = new Database();
$db = $conn->connect();

$setting = new Setting($db);
$setting_value = $setting->getValueSettingSite();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = $_POST['site_name'] ?? '';
    $description = $_POST['description'] ?? '';
    $admin_email = $_POST['admin_email'] ?? '';
    $main_website = $_POST['main_website'] ?? '';

    //ส่วนของการจัดการ logo
    $logo = '';
    if (!empty($_FILES['logo']['name'])) {
        $uploader = new ImageUploader('../assets/images/logo');
        $uploader->setMaxFileSize(5 * 1024 * 1024) // MAX SIZE 5MB
            ->setMaxFiles(1); // Limit based on existing images

        $new_image_files = 'logo' . '_' . 'department';
        $result = $uploader->uploadSingle($_FILES['logo'], $new_image_files);
        $logo = $result['fileName'];
    }

    //ส่วนของการจัดการ icon
    $favicon = '';
    if (!empty($_FILES['favicon']['name'])) {
        $uploader = new ImageUploader('../assets/images/logo');
        $uploader->setMaxFileSize(5 * 1024 * 1024) // MAX SIZE 5MB
            ->setMaxFiles(1); // Limit based on existing images

        $new_image_files = 'icon' . '_' . 'department';
        $result = $uploader->uploadSingle($_FILES['favicon'], $new_image_files);
        $favicon = $result['fileName'];
    }

    //ส่วนของการจัดการ banner
    $image_banner = $_FILES['image_banner'] ?? '';
    $existing_banner = $_POST['existing_banner'] ?? '';
    $delete_banner = $_POST['delete_banner'] ?? '';
    $updated_array_banner = [];
    // ลบไฟล์ banner ถ้ามี
    if (!empty($delete_banner)) {
        $deleted_array = explode(',', $delete_banner);
        $existing_array = explode(',', $existing_banner);

        $uploader = new ImageUploader('../assets/images/banners');
        foreach ($deleted_array as $deleted_image) {
            $uploader->deleteFile($deleted_image);
        }

        // ตัดค่าที่จะลบออกจาก existing
        $updated_array_banner = array_diff($existing_array, $deleted_array);
    } else {
        // ไม่มีการลบ ใช้ existing เดิม
        $updated_array_banner = explode(',', $existing_banner);
    }

    // แปลงเป็น string ก่อนจะอัปโหลดไฟล์ใหม่
    $image_banner_name = implode(',', $updated_array_banner);

    // อัปโหลดรูปภาพ banner ใหม่
    if (!empty($image_banner)) {
        $uploader = new ImageUploader('../assets/images/banners');
        $uploader->setMaxFileSize(5 * 1024 * 1024) // MAX SIZE 5MB
            ->setMaxFiles(4);

        $result = $uploader->uploadMultiple($image_banner, 'banner');

        if ($result['success']) {
            foreach ($result['files'] as $file) {
                $image_banner_name .= ',' . $file['fileName'];
            }
            $image_banner_name = trim($image_banner_name, ',');
        }

        if (!empty($result['errors'])) {
            foreach ($result['errors'] as $error) {
                echo "<script>alert('" . $error . "')</script>";
            }
        }
    }

    // ส่งกลับ json response
    header('Content-Type: application/json');
    echo json_encode($setting->updateSettingSite($site_name, $description, $logo, $favicon, $main_website, $image_banner_name, $admin_email));
    exit;
}


$logo = $setting_value['logo'] ?? '';
$banners = explode(',', $setting_value['banner']);
$site_name = $setting_value['site_name'] ?? '';
$admin_email = $setting_value['admin_email'] ?? '';
$favicon = $setting_value['favicon'] ?? '';
$main_website = $setting_value['main_website'] ?? '';
$description = $setting_value['description'] ?? '';

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <?php include '../includes/title.php'; ?>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../assets/css/style_admin.css" rel="stylesheet">
    <link href="../assets/css/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<style>
    .settings-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .settings-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 8px 8px 0 0;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
    }

    .btn-primary {
        background: #667eea;
        border-color: #667eea;
    }

    .btn-primary:hover {
        background: #5a6fd8;
        border-color: #5a6fd8;
    }

    .color-preview {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #dee2e6;
        display: inline-block;
        margin-left: 10px;
    }

    .section-title {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
</style>

<body>

    <?php include 'includes/sidebar.php' ?>

    <!-- Main Content -->
    <div class="main-content">

        <!-- header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">การตั้งค่าเว็บไซต์</h1>
                <p class="text-muted">จัดการการตั้งค่าทั่วไปของเว็บไซต์ระบบศิษย์เก่า</p>
            </div>
        </div>

        <form id="settingSite" method="post" enctype="multipart/form-data">
            <input type="hidden" name="delete_banner" value="" id="deleteBanner">
            <input type="hidden" name="existing_banner" value="<?php if (!empty($banners[0])) {
                                                                    foreach ($banners as $index => $banner) {
                                                                        if ($index < count($banners) - 1) {
                                                                            echo htmlspecialchars($banner) . ',';
                                                                        } else {
                                                                            echo htmlspecialchars($banner);
                                                                        }
                                                                    }
                                                                } ?>" id="existing_banner">

            <div class="mb-3">
                <label class="form-label">ชื่อเว็บไซต์</label>
                <input type="text" name="site_name" class="form-control" placeholder="ชื่อเว็บไซต์" value="<?php echo $site_name ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">คำอธิบายเว็บไซต์</label>
                <textarea class="form-control" name="description" rows="3" placeholder="คำอธิบายเว็บไซต์"><?php echo $description ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">โลโก้เว็บไซต์</label>
                <input class="form-control" id="logo" name="logo" type="file" accept="image/*">
                <div class="form-text">แนะนำขนาด 200x60 พิกเซล</div>
                <div class="image-preview-logo">
                    <img id="previewLogo" src="../assets/images/logo/<?php echo $logo ?>" style="width: 100px;">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">ไอคอน (Favicon)</label>
                <input class="form-control" id="icon" name="favicon" type="file" accept="image/*">
                <div class="form-text">แนะนำขนาด 32x32 พิกเซล</div>
                <div class="image-preview-logo">
                    <img id="previewIcon" src="../assets/images/logo/<?php echo $favicon ?>" style="width: 100px;">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">แบนเนอร์หน้าแรกเว็บไซต์ (เลือกได้สูงสุด 4 ภาพ)</label>
                <input class="form-control" id="banners" type="file" name="banners[]" multiple accept="image/*">
                <div class="form-text">แนะนำขนาด 1200*800 พิกเซล</div>
                <div class="image-preview-banner">
                    <div class="d-flex gap-2">
                        <?php if (!empty($banners[0])) {
                            foreach ($banners as $banner) { ?>
                                <div class="image-item-banner position-relative d-inline-block me-2 mb-2">
                                    <img src="../assets/images/banners/<?php echo $banner ?>" style="width: 100px;">
                                    <button type="button" class="btn btn-danger btn-sm delete-image-btn position-absolute top-0 end-0"
                                        data-image="<?php echo htmlspecialchars($banner); ?>"
                                        data-type="existing"
                                        style="transform: translate(50%, -50%); border-radius: 50%; width: 25px; height: 25px; padding: 0;">
                                        <i class="fas fa-times" style="font-size: 12px;"></i>
                                    </button>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">เว็บไซต์หลักวิทยาลัย</label>
                <input type="url" name="main_website" class="form-control" value="<?php echo $main_website ?>" placeholder="https://www.university.ac.th">
            </div>
            <div class="mb-3">
                <label class="form-label">Email ผู้ดูแลระบบ</label>
                <input type="url" name="admin_email" class="form-control" value="<?php echo $admin_email ?>">
            </div>
            <div class="d-flex justify-content-end gap-2 mt-4">
                <button id="cancelSettings" type="button" class="btn btn-secondary">ยกเลิก</button>
                <button id="saveSettings" type="button" class="btn btn-success">บันทึกการตั้งค่า</button>
            </div>
        </form>
    </div>

    <script src="../assets/js/bootstrap.bundle.js"></script>
    <script src="../assets/js/script_admin.js"></script>
    <script src="../assets/js/sweetalert2.all.min.js"></script>
    <script src="../assets/alerts/modal.js"></script>
    <script>
        
        //บันทึกการตั้งค่า
        document.getElementById('saveSettings').addEventListener('click', () => {
            const form = document.getElementById('settingSite');
            const formData = new FormData(form);

            for (let i = 0; i < imageBanners.length; i++) {
                formData.append('image_banner[]', imageBanners[i]);
            }

            modalConfirm('ยืนยันเปลี่ยนแปลงการตั้งค่าเว็บไซต์', 'ยืนยันเปลี่ยนแปลงการตั้งค่าเว็บไซต์')
                .then(result => {
                    if (result.isConfirmed) {
                        fetch('setting.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(response => {
                                if (response.result) {
                                    modalAlert('อัปเดตการตั้งค่าเว็บไซต์สำเร็จ', 'อัปเดตการตั้งค่าเว็บไซต์สำเร็จ', 'success')
                                        .then(() => {
                                            location.reload();
                                        })
                                } else {
                                    modalAlert('เกิดข้อผิดพลาด', response.message, 'error')
                                }
                            })
                            .catch(err => {
                                console.error('เกิดข้อผิดพลาด:', err);
                            });
                    }
                })
        })

        //ยกเลิกการตั้งค่า
        document.getElementById('cancelSettings').addEventListener('click', () => {
            modalConfirm('ยกเลิกการตั้งค่า', 'คุณต้องการยกเลิกการตั้งค่าใช่หรือไม่ ระบบจะไม่ได้บันทึกการเปลี่ยนแปลงของคุณไว้?')
            .then(result =>{
                if(result.isConfirmed){
                    window.location.href = 'index.php';
                }
            })
        })

        //เปลี่ยนโลโก้
        function changeLogo() {
            document.getElementById('logo').addEventListener('change', (e) => {
                const previewLogo = document.getElementById('previewLogo');
                const file = e.target.files[0];

                if (file) {
                    const url = URL.createObjectURL(file);
                    previewLogo.src = url;
                }
            });
        }

        //เปลี่ยนไอคอน
        function changeIcon() {
            document.getElementById('icon').addEventListener('change', (e) => {
                const previewIcon = document.getElementById('previewIcon');
                const file = e.target.files[0];

                if (file) {
                    const url = URL.createObjectURL(file);
                    previewIcon.src = url;
                }
            });
        }

        changeLogo();
        changeIcon();

        let imageBanners = []; //เก็บข้อมูลรูปภาพแบนเนอร์ที่จะอัปโหลด

        //เพิ่มรูปภาพใหม่
        function addBannerPreview() {
            const input = document.getElementById('banners');
            input.addEventListener('change', function(e) {
                const files = Array.from(e.target.files); // แปลง FileList เป็น Array
                const previewContainer = document.querySelector('.image-preview-banner .d-flex');
                const currentImages = document.querySelectorAll('.image-item-banner').length;
                const totalImages = currentImages + files.length;

                if (totalImages > 4) {
                    modalAlert('เกิดข้อผิดพลาด', 'อัพโหลดรูปภาพแบนเนอร์ได้สูงสุด 4 ภาพ', 'error');
                    input.value = '';
                    return;
                }

                files.forEach((file, index) => {
                    // เพิ่มไฟล์ลง array
                    imageBanners.push(file);

                    console.log(imageBanners);

                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const div = document.createElement('div');
                        div.className = "image-item-banner position-relative d-inline-block me-2 mb-2";

                        // เพิ่ม data-index เพื่อรู้ว่าไฟล์ไหนตรงกับ preview อะไร
                        div.innerHTML = `
                    <img src="${event.target.result}" style="width: 100px;">
                    <button type="button" class="btn btn-danger btn-sm delete-image-btn position-absolute top-0 end-0" 
                        data-type="new"
                        data-index="${imageBanners.length - 1}" 
                        style="transform: translate(50%, -50%); border-radius: 50%; width: 25px; height: 25px; padding: 0;">
                        <i class="fas fa-times" style="font-size: 12px;"></i>
                    </button>
                `;
                        previewContainer.appendChild(div);
                    }

                    reader.readAsDataURL(file);
                });

                input.value = '';
            });
        }

        //ลบรูปภาพแบนเนอร์
        function deleteBanner() {
            document.querySelector('.image-preview-banner .d-flex').addEventListener('click', function(e) {
                const btn = e.target.closest('.delete-image-btn');
                if (!btn) return;

                const type = btn.getAttribute('data-type');
                const imageItem = btn.closest('.image-item-banner');

                if (type === 'new') {
                    const index = btn.getAttribute('data-index');
                    if (index !== null && imageBanners[index]) {
                        imageBanners[index] = null;
                        //console.log('ลบภาพใหม่จาก imageBanners[] ตำแหน่ง', index);
                        //console.log(imageBanners);
                    }
                } else if (type === 'existing') {
                    const imageName = btn.getAttribute('data-image');
                    const deleteBannerInput = document.getElementById('deleteBanner');
                    if (deleteBannerInput.value !== '') {
                        deleteBannerInput.value += ',' + imageName;
                    } else {
                        deleteBannerInput.value = imageName;
                    }
                }

                //ลบ element
                if (imageItem) imageItem.remove();
            });

        }

        addBannerPreview();
        deleteBanner();
    </script>
</body>

</html>