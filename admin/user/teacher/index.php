<?php
include '../../../config/config.php';
require_once '../../../classes/admin.php';
require_once '../../../classes/pagination_helper.php';

$db = new Database();
$conn = $db->connect();
$teacher = new Admin($conn, 'teacher');

// ตั้งค่าพื้นฐาน
$currentPage   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage  = 10;

// รับค่าการค้นหา
$keyword    = trim($_GET['keyword'] ?? '');
$start_date = $_GET['start_date'] ?? '';
$end_date   = $_GET['end_date'] ?? '';

// ถ้ามีการกรองข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'GET' && (!empty($keyword) || !empty($start_date) || !empty($end_date))) {

    // นับจำนวนรายการที่ตรงกับเงื่อนไขการค้นหา
    $totalItems = $teacher->getSearchAndFilterCount($keyword,  $start_date, $end_date);

    // สร้าง pagination
    $pagination = new PaginationHelper($currentPage, $itemsPerPage, $totalItems);

    // ดึงข่าวตามเงื่อนไข
    $teacher_list = $teacher->searchAndFilter($keyword,  $start_date, $end_date, $pagination->getLimit(), $pagination->getOffset());
} else {
    // นับจำนวนรายการทั้งหมด
    $totalItems = $teacher->getTotalCount();

    // สร้าง pagination
    $pagination = new PaginationHelper($currentPage, $itemsPerPage, $totalItems);

    // ดึงข่าวทั้งหมด
    $teacher_list = $teacher->getAll(
        $pagination->getLimit(),
        $pagination->getOffset()
    );
}

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../../../assets/css/style_admin.css" rel="stylesheet">
    <link href="../../../assets/css/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

</head>
<style>
    .search-filter-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        padding-left: 2.5rem;
        border-radius: 10px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .search-box .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .filter-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .table img {
        max-width: 200px;
        min-width: 100px;
    }
</style>

<body>

    <?php include '../../includes/sidebar.php' ?>

    <div class="main-content">
        <!-- Heading -->
        <div class="d-flex">
            <h3 class="table-header mb-5"><b>จัดการข้อมูลครู/อาจารย์</b></h3>
            <div>
                <a href="add.php" class="btn btn-success mx-3">เพิ่มข้อมูลครู / อาจารย์</a>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <form method="GET" class="search-filter-section">
            <div class="row align-items-end">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">ค้นหา</label>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" value="<?php echo htmlspecialchars($keyword ?? ''); ?>" name="keyword" class="form-control" placeholder="ค้นหารายชื่อ รหัสผู้ใช้งาน และอื่นๆ...">
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-bold">วันที่เริ่มต้น</label>
                    <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date ?? ''); ?>" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-bold">วันที่สิ้นสุด</label>
                    <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date ?? ''); ?>" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>ค้นหา
                        </button>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-redo me-1"></i>รีเซ็ต
                        </a>
                    </div>
                </div>
            </div>
        </form>



        <!-- Tables List -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ลำดับที่</th>
                            <th>โปรไฟล์</th>
                            <th>ชื่อผู้ใช้งาน</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>อิเมลล์</th>
                            <th>เบอร์โทรศัพท์</th>
                            <th>ตำแหน่ง</th>
                            <th>วันที่ลงทะเบียน</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (!empty($teacher_list)) {
                            $i = 1;
                            foreach ($teacher_list as $item) {
                                $id = $item['admin_id'];
                                $username = $item['username'];
                                $first_name = $item['first_name'];
                                $last_name = $item['last_name'];
                                $full_name = $first_name . ' ' . $last_name;
                                $email = $item['email'];
                                $phone = $item['phone'];
                                $position = $item['position'];
                                $image = !empty($item['image']) ? $item['image'] : "";
                                $created_at = date('d/m/Y H:i', strtotime($item['created_at']));

                                $isset_image = $image != '' ? '../../../assets/images/user/teacher/' . $image : '../../../assets/images/user/no-image-profile.jpg';
                        ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><img class="img-thumbnail w-50 h-auto" src="<?php echo $isset_image ?>"></td>
                                    <td class="text-center"><?php echo htmlspecialchars($username); ?></td>
                                    <td><?php echo htmlspecialchars($full_name); ?></td>
                                    <td><?php echo htmlspecialchars($email); ?></td>
                                    <td><?php echo htmlspecialchars($phone); ?></td>
                                    <td><?php echo htmlspecialchars($position); ?></td>
                                    <td><?php echo htmlspecialchars($created_at); ?></td>
                                    <td class="text-center">
                                        <button class="action-btn btn-outline-primary"
                                            onclick="modalEdit(
                                                <?php echo $id; ?>, 
                                                '<?php echo htmlspecialchars($username, ENT_QUOTES); ?>', 
                                                '<?php echo htmlspecialchars($first_name, ENT_QUOTES); ?>', 
                                                '<?php echo htmlspecialchars($last_name, ENT_QUOTES); ?>', 
                                                '<?php echo htmlspecialchars($email, ENT_QUOTES); ?>', 
                                                '<?php echo htmlspecialchars($phone, ENT_QUOTES); ?>', 
                                                '<?php echo htmlspecialchars($position, ENT_QUOTES); ?>', 
                                                '<?php echo $image ?>', 
                                                '<?php echo htmlspecialchars($created_at, ENT_QUOTES); ?>',
                                                '<?php echo $isset_image ?>'
                                            )"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditData">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn btn-outline-danger" onclick="deleteData(<?php echo $id; ?>, 'id=<?php echo $id ?>&image=<?php echo $item['image'] ?>', 'delete.php')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                        <?php }
                        } ?>


                    </tbody>
                </table>
                <!--ภ้าไม่มีข้อมูลให้แสดงคำว่า ไม่พบข้อมูลนักเรียน -->
                <?php if (empty($teacher_list)) { ?>
                    <div class="text-center text-danger">ไม่พบข้อมูลครู/อาจารย์</div>
                <?php } ?>

            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            <div class="row align-items-center">
                <?php

                echo $pagination->renderBootstrap();

                ?>
            </div>
        </div>
    </div>

    <!-- Modal for Edit -->
    <div class="modal fade" id="modalEditData" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <!-- ส่วนหัว -->
                <div class="modal-header">
                    <h5 class="modal-title text-warning" id="modalTitle">แก้ไขข้อมูล ครู/อาจารย์</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- ส่วนเนื้อหา -->
                <div class="modal-body">
                    <form id="editForm" enctype="multipart/form-data">
                        <input type="hidden" name="deleted_images" id="deletedImages" value="">
                        <input type="hidden" name="current_images" id="currentImages" value="">

                        <div class="row">
                            <div class="col-md-4 mx-auto text-center">
                                <img id="imagePreview" src="" class="img-thumbnail mb-2" style="width: 80%; object-fit: cover;">
                                <label class="btn btn-primary">
                                    เลือกรูปภาพ
                                    <input type="file" name="image" id="editProfilePic" accept="image/*" hidden>
                                </label>
                                <small class="text-muted d-block mt-2">
                                    รองรับ JPG, PNG, GIF<br>
                                    ขนาดไม่เกิน 5MB
                                </small>
                            </div>

                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">รหัสผู้ใช้งาน</label>
                                        <input type="text" class="form-control" id="editId" name="id" readonly required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ชื่อผู้ใช้งาน</label>
                                        <input type="text" class="form-control" id="editUsername" name="username" required>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ชื่อ</label>
                                        <input type="text" class="form-control" id="editFirstName" name="first_name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">นามสกุล</label>
                                        <input type="text" class="form-control" id="editLastName" name="last_name" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">อีเมล</label>
                                    <input type="email" class="form-control" id="editEmail" name="email" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">เบอร์โทรศัพท์</label>
                                        <input type="tel" class="form-control" id="editPhone" name="phone" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ตำแหน่ง</label>
                                        <select class="form-select" name="position" id="editPosition" required>
                                            <option value="">เลือก</option>
                                            <option value="หัวหน้าแผนกวิชา">หัวหน้าแผนกวิชา</option>
                                            <option value="ครูผู้ช่วย">ครูผู้ช่วย</option>
                                            <option value="ครูอัตราจ้าง">ครูอัตราจ้าง</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">วันที่สร้างบัญชี</label>
                                    <input type="text" class="form-control" id="editDateRegister" disabled name="date_register" required>
                                </div>
                            </div>
                        </div>


                    </form>

                </div>

                <!-- ส่วนปุ่ม -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-success" onclick="update()">บันทึก</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="../../../assets/js/bootstrap.bundle.js"></script>
    <script src="../../../assets/js/script_admin.js"></script>
    <script src="../../../assets/js/sweetalert2.all.min.js"></script>
    <script src="../../../assets/alerts/modal.js"></script>
    <script src="../../functions/delete_data.js"></script>
    <script src="../../functions/update_data.js"></script>
    <script>
        function modalEdit(id, username, first_name, last_name, email, phone, position, image, created_at, issetImage) {
            // กำหนดค่าให้กับฟอร์ม Edit Modal
            document.getElementById('editId').value = id;
            document.getElementById('editUsername').value = username;
            document.getElementById('editFirstName').value = first_name;
            document.getElementById('editLastName').value = last_name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editPhone').value = phone;
            document.getElementById('editPosition').value = position;
            document.getElementById('editDateRegister').value = created_at;

            document.getElementById('imagePreview').src = issetImage;
            document.getElementById('currentImages').value = image;
        }

        const form = document.getElementById('editForm');

        function update() {
            const id = document.getElementById('editId').value;
            const formData = new FormData(form);
            //const formParams = new URLSearchParams(formData);

            updateData(id, formData, 'edit.php');
        }

        const input = document.getElementById('editProfilePic');

        input.addEventListener('change', function() {
            const file = this.files[0];

            const preview = document.getElementById('imagePreview');
            const deleteImage = document.getElementById('deletedImages');
            const currentImage = document.getElementById('currentImages');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);

                if (currentImage.value != '../../../assets/images/user/no-image-profile.jpg') {
                    deleteImage.value = currentImage.value
                }

            }
        });

        const previewImg = document.getElementById('imagePreview');
        const currentImage = document.getElementById('currentImages'); // hidden input ที่เก็บชื่อรูปเดิม

        // ดักเหตุการณ์เมื่อ modal ถูกปิด
        form.addEventListener('hide.bs.modal', function() {
            // ล้างค่า input file
            input.value = "";

            // รีเซ็ต preview กลับเป็นรูปเดิม
            currentImage.value = "";

            // ล้าง input hidden ที่เตรียมลบไฟล์ (ถ้ามี)
            document.getElementById('deletedImages').value = "";
        });
    </script>
</body>

</html>