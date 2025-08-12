<?php 
header('Content-Type: application/json');
require_once '../../../auth/auth_admin.php';
require_once '../../../classes/student.php';
require_once '../../../classes/image_uploader.php';

$db = new Database();
$conn = $db->connect();

$student = new Student($conn);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = $_POST['id'];
    $student_code = $_POST['student_code'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $education_level = $_POST['education_level'];
    $status_register = $_POST['status_register'];

    $address = $_POST['address'];
    $facebook = $_POST['facebook'];
    $instagram = $_POST['instagram'];
    $line = $_POST['line'];
    $tiktok = $_POST['tiktok'];

    $image = $_FILES['image'];
    $current_images = $_POST['current_images'];
    $deleted_images = $_POST['deleted_images'];

    $password = $_POST['password'];

    if (!empty($deleted_images)) {     
        // Delete physical files using ImageUploader's deleteFile method
        $uploader = new ImageUploader('../../../assets/images/user/student');
        $uploader->deleteFile($deleted_images);
    }

    $new_image_files = '';
    if (!empty($image['name'])) {
        $uploader = new ImageUploader('../../../assets/images/user/student');
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

    $result = $student->editStudent($id, $student_code, $first_name, $last_name, $email, $phone, $education_level, $status_register, $new_image_files
                                     ,$address, $facebook, $instagram, $line, $tiktok, $password);

    echo json_encode($result);
    
}

exit;


?>