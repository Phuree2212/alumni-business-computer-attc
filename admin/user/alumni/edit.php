<?php 
header('Content-Type: application/json');
require_once '../../../auth/auth_admin.php';
require_once '../../../classes/alumni.php';
require_once '../../../classes/image_uploader.php';


$db = new Database();
$conn = $db->connect();

$alumni = new Alumni($conn);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = $_POST['id'];
    $student_code = $_POST['student_code'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $status_education = $_POST['status_education'];
    $education_level = $_POST['education_level'];
    $graduation_year = $_POST['graduation_year'];
    $status_register = $_POST['status_register'];
    $current_job = $_POST['current_job'];
    $current_company = $_POST['current_company'];
    $current_salary = $_POST['current_salary'];  
    
    $image = $_FILES['image'];
    $current_images = $_POST['current_images'];
    $deleted_images = $_POST['deleted_images'];

    if (!empty($deleted_images)) {     
        // Delete physical files using ImageUploader's deleteFile method
        $uploader = new ImageUploader('../../../assets/images/user/alumni');
        $uploader->deleteFile($deleted_images);
    }

    $new_image_files = '';
    if (!empty($image['name'])) {
        $uploader = new ImageUploader('../../../assets/images/user/alumni');
        $uploader->setMaxFileSize(5 * 1024 * 1024) // MAX SIZE 5MB
            ->setMaxFiles(1); // Limit based on existing images

        $new_image_files .= $student_code . '_' . $first_name;
        $result = $uploader->uploadSingle($_FILES['image'], $new_image_files);
        $new_image_files = $result['fileName'];
        
        //echo json_encode($result);
    }else{
        //ถ้าไม่มีการอัพโหลดรูปภาพใหม่ ให้ใช้ภาพเดิม
        $new_image_files = $current_images;
    }


    $result = $alumni->editAlumni($id, $student_code, $first_name, $last_name, $email, $phone, $education_level, 
            $graduation_year, $status_register, $current_job, $current_company, $current_salary, $new_image_files, $status_education);

    echo json_encode($result);
    
}

exit;


?>