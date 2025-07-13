<?php
require_once '../../auth/auth_admin.php'; 
require_once '../../classes/activities.php';
require_once '../../classes/image_uploader.php';

$db = new Database();
$conn = $db->connect();

$activity = new Activities($conn);

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['image'])){
    $id = $_POST['id'];
    $string_image = $_POST['image'];

    //ลบไฟล์รูปภาพใน server
    if (!empty($string_image)) {
        $deleted_array = explode(',', $string_image);
        
        //เรียกใช้ฟังก์ชัน ImageUploader เพื่อใส่ที่อยู่ไฟล์ที่ต้องการลบ
        $uploader = new ImageUploader('../../assets/images/news');
        foreach ($deleted_array as $deleted_image) {
            $uploader->deleteFile($deleted_image);
        }
    }

    //ลบแถวข้อมูลใน Database
    $result = $activity->deleteActivity($id);
    
    echo json_encode($result); //response
}

?>