<?php 
header('Content-Type: application/json');
require_once '../../auth/auth_all.php';
require_once '../../classes/webboard.php';
require_once '../../classes/image_uploader.php';

$db = new Database();
$conn = $db->connect();

$webboard = new Webboard($conn);

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])){
    $id = $_POST['id'];
    $string_image = $_POST['image'];

    if (!empty($string_image)) {     
        // Delete physical files using ImageUploader's deleteFile method
        $uploader = new ImageUploader('../../assets/images/webboard');
        $uploader->deleteFile($string_image);
    }

    $result = $webboard->deleteTopic($id);

    //ลบแถวข้อมูลใน Database
    echo json_encode($result); //response
}

exit;

?>
