<?php
require_once '../../../auth/auth_admin.php';
require_once '../../../classes/alumni.php';
require_once '../../../classes/pagination_helper.php';


$db = new Database();
$conn = $db->connect();
$alumni = new Alumni($conn);

// ตั้งค่าพื้นฐาน
$currentPage   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage  = 10;

// รับค่าการค้นหา
$keyword    = trim($_GET['keyword'] ?? '');
$education_level = $_GET['education_level'] ?? '';
$graduation_year = $_GET['graduation_year'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date   = $_GET['end_date'] ?? '';

// ถ้ามีการกรองข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'GET' && (!empty($keyword) || !empty($education_level) || !empty($graduation_year) || !empty($start_date) || !empty($end_date))) {

    // นับจำนวนรายการที่ตรงกับเงื่อนไขการค้นหา
    $totalItems = $alumni->getSearchAndFilterCount($keyword, $education_level, $graduation_year, $start_date, $end_date);

    // สร้าง pagination
    $pagination = new PaginationHelper($currentPage, $itemsPerPage, $totalItems);

    // ดึงข่าวตามเงื่อนไข
    $alumni_list = $alumni->searchAndFilterAlumni($keyword, $education_level, $graduation_year, $start_date, $end_date, $pagination->getLimit(), $pagination->getOffset());
} else {
    // นับจำนวนรายการทั้งหมด
    $totalItems = $alumni->getTotalCount();

    // สร้าง pagination
    $pagination = new PaginationHelper($currentPage, $itemsPerPage, $totalItems);

    // ดึงข่าวทั้งหมด
    $alumni_list = $alumni->getAllAlumni(
        $pagination->getLimit(),
        $pagination->getOffset()
    );
}

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <?php include '../../../includes/title.php'; ?>
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
            <h3 class="table-header mb-5"><b>จัดการข้อมูลศิษย์เก่า</b></h3>
            <div>
                <a href="add.php" class="btn btn-success mx-3">เพิ่มข้อมูลศิษย์เก่า</a>
                <span class="text-danger">*เพิ่มในเวลาที่เกิดปัญหาในการลงทะเบียนสมาชิกเท่านั้น</span>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <form method="GET" class="search-filter-section">
            <div class="row align-items-end">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">ค้นหา</label>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" value="<?php echo htmlspecialchars($keyword ?? ''); ?>" name="keyword" class="form-control" placeholder="ค้นหารายชื่อ รหัสนักเรียน และอื่นๆ...">
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-bold">ระดับชั้นที่จบการศึกษา</label>
                    <select name="education_level" class="form-select">
                        <option selected value="">ทั้งหมด</option>
                        <option <?php echo $education_level == 'ปวช.3' ? 'selected' : '' ?> value="ปวช.3">ปวช.3</option>
                        <option <?php echo $education_level == 'ปวส.2' ? 'selected' : '' ?> value="ปวส.2">ปวส.2</option>
                    </select>
                </div>
                <div class="col-md-1 mb-3">
                    <label class="form-label fw-bold">ปีที่จบการศึกษา</label>
                    <select name="graduation_year" class="form-select">
                        <option selected value="">ทั้งหมด</option>
                        <?php $year = 2540; ?>
                        <?php for ($i = $year; $i <= date('Y') + 543; $i++) { ?>
                            <option <?php echo $graduation_year == $i ? 'selected' : '' ?> value="<?php echo $i ?>"><?php echo $i ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-bold">วันที่เริ่มต้น</label>
                    <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date ?? ''); ?>" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-bold">วันที่สิ้นสุด</label>
                    <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date ?? ''); ?>" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
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
                            <th>รหัสนักศึกษา</th>
                            <th>โปรไฟล์</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>อิเมลล์</th>
                            <th>เบอร์โทรศัพท์</th>
                            <th>ระดับชั้นที่จบการศึกษา</th>
                            <th>ปีที่จบการศึกษา</th>
                            <th>สถานะ</th>
                            <th>สถานะการศึกษาต่อ</th>
                            <th>วันที่ลงทะเบียน</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (!empty($alumni_list)) {
                            $i = 1;
                            foreach ($alumni_list as $item) {
                                $id = $item['user_id'];
                                $student_code = $item['student_code'];
                                $first_name = $item['first_name'];
                                $last_name = $item['last_name'];
                                $full_name = $item['first_name'] . ' ' . $item['last_name'];
                                $email = $item['email'];
                                $phone = $item['phone'];
                                $education_level = $item['education_level'];
                                $graduation_year = $item['graduation_year'];
                                $status_register = $item['status_register'] == 1 ? 'ผู้ใช้งาน' : '';
                                $status_education = !empty($item['status_education']) ? $item['status_education'] : 'ไม่มีข้อมูล';
                                $current_job = !empty($item['current_job']) ? $item['current_job'] : "";
                                $current_company = !empty($item['current_company']) ? $item['current_company'] : "";
                                $current_salary = !empty($item['current_salary']) ? $item['current_salary'] : "";
                                $image = !empty($item['image']) ? $item['image'] : "";
                                $created_at = date('d/m/Y H:i', strtotime($item['created_at']));

                                $address = $item['address'];
                                $facebook = $item['facebook'];
                                $instagram = $item['instagram'];
                                $tiktok = $item['tiktok'];
                                $line = $item['line'];

                                $isset_image = $image != '' ? '../../../assets/images/user/alumni/' . $image : '../../../assets/images/user/no-image-profile.jpg';
                        ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($student_code); ?></td>
                                    <td><img class="img-thumbnail w-50 h-auto" src="<?php echo $isset_image ?>"></td>
                                    <td><?php echo htmlspecialchars($full_name); ?></td>
                                    <td><?php echo htmlspecialchars($email); ?></td>
                                    <td><?php echo htmlspecialchars($phone); ?></td>
                                    <td><?php echo htmlspecialchars($education_level); ?></td>
                                    <td><?php echo htmlspecialchars($graduation_year); ?></td>
                                    <td><?php echo htmlspecialchars($status_register); ?></td>
                                    <td><?php echo htmlspecialchars($status_education); ?></td>
                                    <td><?php echo htmlspecialchars($created_at); ?></td>
                                    <td class="text-center">
                                        <button class="action-btn btn-outline-primary"
                                            onclick="modalEditAlumni(
                                                <?php echo $id; ?>, 
                                                '<?php echo htmlspecialchars($student_code, ENT_QUOTES); ?>', 
                                                '<?php echo htmlspecialchars($first_name, ENT_QUOTES); ?>', 
                                                '<?php echo htmlspecialchars($last_name, ENT_QUOTES); ?>', 
                                                '<?php echo htmlspecialchars($email, ENT_QUOTES); ?>', 
                                                '<?php echo htmlspecialchars($phone, ENT_QUOTES); ?>', 
                                                '<?php echo htmlspecialchars($education_level, ENT_QUOTES); ?>',
                                                '<?php echo htmlspecialchars($graduation_year, ENT_QUOTES); ?>',  
                                                <?php echo $item['status_register']; ?>,
                                                '<?php echo htmlspecialchars($status_education, ENT_QUOTES); ?>', 
                                                '<?php echo $image ?>',
                                                '<?php echo htmlspecialchars($current_job, ENT_QUOTES); ?>',
                                                '<?php echo htmlspecialchars($current_company, ENT_QUOTES); ?>',
                                                '<?php echo htmlspecialchars($current_salary, ENT_QUOTES); ?>',
                                                '<?php echo htmlspecialchars($created_at, ENT_QUOTES); ?>',
                                                '<?php echo $isset_image ?>',
                                                '<?php echo $address ?>',
                                                '<?php echo $facebook ?>',
                                                '<?php echo $instagram ?>',
                                                '<?php echo $tiktok ?>',
                                                '<?php echo $line ?>',
                                            )"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalManageData">
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
                <?php if (empty($alumni_list)) { ?>
                    <div class="text-center text-danger">ไม่พบข้อมูลนักเรียน</div>
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

    <!-- Modal for Edit Student -->
    <div class="modal fade" id="modalManageData" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <!-- ส่วนหัว -->
                <div class="modal-header">
                    <h5 class="modal-title text-warning" id="modalTitle">แก้ไขข้อมูลศิษย์เก่า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- ส่วนเนื้อหา -->
                <div class="modal-body">
                    <form id="editAlumniForm" enctype="multipart/form-data">
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
                                <div class="detail-card-header mb-2">
                                    <i class="fas fa-user me-2"></i>ข้อมูลส่วนตัว
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">รหัสผู้ใช้งาน</label>
                                        <input type="text" class="form-control" id="editAlumniId" name="id" readonly required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">รหัสนักศึกษา</label>
                                        <input type="text" class="form-control" id="editStudentCode" name="student_code" required>
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
                                        <label class="form-label">ระดับชั้นที่จบการศึกษา</label>
                                        <select class="form-select" name="education_level" id="editEducationLevel" required>
                                            <option value="">เลือกระดับชั้น</option>
                                            <option value="ปวช.3">ปวช.3</option>
                                            <option value="ปวส.2">ปวส.2</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">ที่อยู่</label>
                                    <textarea class="form-control" id="editAddress" name="address"></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ปีที่จบการศึกษา</label>
                                        <input type="number" class="form-control" id="editGraduationYear" name="graduation_year" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">สถานะการศึกษาต่อ</label>
                                        <select class="form-select" name="status_education" id="editStatusEducation" required>
                                            <option value="">เลือก</option>
                                            <option value="ศึกษาต่อ">ศึกษาต่อ</option>
                                            <option value="ทำงานแล้ว">ทำงานแล้ว</option>
                                            <option value="ว่างงาน">ว่างงาน</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">สถานะ</label>
                                    <select class="form-select" id="editStatus" name="status_register" required>
                                        <option value="1">อณุญาติการใช้งาน</option>
                                        <option value="2">รอดำเนินการอนุมัติ</option>
                                        <option value="0">ระงับการใข้งาน</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">วันที่เริ่มเป็นสมาขิก</label>
                                    <input type="text" class="form-control" id="editDateRegister" disabled name="date_register" required>
                                </div>

                                <hr>

                                <div class="detail-card-header mb-2">
                                    <i class="fas fa-briefcase me-2"></i>ข้อมูลการทำงาน
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ตำแหน่งงานปัจจุบัน</label>
                                        <input type="text" class="form-control" id="editCurrentJob" name="current_job" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">ชื่อบริษัท/สถานที่ทำงาน</label>
                                        <input type="text" class="form-control" id="editCurrentCompany" name="current_company" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">เงินเดือนปัจจุบัน</label>
                                    <input type="number" class="form-control" id="editCurrentSalary" name="current_salary" required>
                                </div>

                                <hr>

                                <div class="detail-card-header mb-2">
                                    <i class="fas fa-address-book me-2"></i>ข้อมูลการติดต่อ
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Facebook ลิงค์</label>
                                    <input type="text" class="form-control" id="editFacebook" name="facebook" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Instagram ลิงค์</label>
                                    <input type="text" class="form-control" id="editInstagram" name="instagram" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Line ID ลิงค์</label>
                                    <input type="text" class="form-control" id="editLine" name="line" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tiktok ลิงค์</label>
                                    <input type="text" class="form-control" id="editTiktok" name="tiktok" required>
                                </div>




                            </div>
                        </div>



                    </form>
                </div>

                <!-- ส่วนปุ่ม -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-success" onclick="updateAlumni()">บันทึก</button>
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
        function modalEditAlumni(id, student_code, first_name, last_name, email, phone, education_level, graduation_year, status_register, status_education, image,
            current_job, current_company, current_salary, created_at, issetImage, address, facebook, instagram, tiktok, line) {
            // กำหนดค่าให้กับฟอร์ม Edit Modal
            document.getElementById('editAlumniId').value = id;
            document.getElementById('editStudentCode').value = student_code;
            document.getElementById('editFirstName').value = first_name;
            document.getElementById('editLastName').value = last_name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editPhone').value = phone;
            document.getElementById('editEducationLevel').value = education_level;
            document.getElementById('editGraduationYear').value = graduation_year;
            document.getElementById('editStatus').value = status_register;
            document.getElementById('editStatusEducation').value = status_education == "ไม่มีข้อมูล" ? "" : status_education;
            document.getElementById('imagePreview').src = issetImage;
            document.getElementById('editCurrentJob').value = current_job;
            document.getElementById('editCurrentCompany').value = current_company;
            document.getElementById('editCurrentSalary').value = current_salary;
            document.getElementById('editDateRegister').value = created_at;

            document.getElementById('editAddress').value = address;
            document.getElementById('editFacebook').value = facebook;
            document.getElementById('editInstagram').value = instagram;
            document.getElementById('editTiktok').value = tiktok;
            document.getElementById('editLine').value = line;

            document.getElementById('currentImages').value = image;
        }

        const form = document.getElementById('editAlumniForm');

        function updateAlumni() {
            const id = document.getElementById('editAlumniId').value;
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