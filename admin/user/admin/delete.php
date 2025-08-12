<?php 
header('Content-Type: application/json');
require_once '../../../auth/auth_admin.php';
require_once '../../../classes/admin.php';
require_once '../../../classes/webboard.php';
require_once '../../../classes/image_uploader.php';

$db = new Database();
$conn = $db->connect();

$admin = new Admin($conn, 'admin');
$webboard = new Webboard($conn);

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])){
    $id = $_POST['id'];
    $string_image = $_POST['image'];
    $user_type = $_POST['user_type'];

    if (!empty($string_image)) {     
        // Delete physical files using ImageUploader's deleteFile method
        $uploader = new ImageUploader('../../../assets/images/user/admin');
        $uploader->deleteFile($string_image);
    }

    $webboard->deleteTopicUser($id, $user_type); //ลบกระทู้ของ user ที่ถูกลบ
    $result = $admin->delete($id);

    //ลบแถวข้อมูลใน Database
    echo json_encode($result); //response
}

exit;


?>