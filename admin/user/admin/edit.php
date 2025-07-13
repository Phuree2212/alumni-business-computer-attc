<?php 
header('Content-Type: application/json');
require_once '../../../config/config.php';
require_once '../../../classes/admin.php';
require_once '../../../classes/image_uploader.php';


$db = new Database();
$conn = $db->connect();

$admin = new Admin($conn, 'admin');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = $_POST['id'];
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];

    $image = $_FILES['image'];
    $current_images = $_POST['current_images'];
    $deleted_images = $_POST['deleted_images'];

    if (!empty($deleted_images)) {     
        // Delete physical files using ImageUploader's deleteFile method
        $uploader = new ImageUploader('../../../assets/images/user/admin');
        $uploader->deleteFile($deleted_images);
    }

    $new_image_files = '';
    if (!empty($image['name'])) {
        $uploader = new ImageUploader('../../../assets/images/user/admin');
        $uploader->setMaxFileSize(5 * 1024 * 1024) // MAX SIZE 5MB
            ->setMaxFiles(1); // Limit based on existing images

        $new_image_files .= $first_name . '_' . $last_name;
        $result = $uploader->uploadSingle($_FILES['image'], $new_image_files);
        $new_image_files = $result['fileName'];
        
        //echo json_encode($result);
    }else{
        //ถ้าไม่มีการอัพโหลดรูปภาพใหม่ ให้ใช้ภาพเดิม
        $new_image_files = $current_images;
    }

    $result = $admin->edit($id,$username,$email, $first_name, $last_name, $phone, $position, $new_image_files);

    echo json_encode($result);
    
}

exit;


?>