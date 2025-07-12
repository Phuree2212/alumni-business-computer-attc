<?php 
header('Content-Type: application/json');
include '../../../config/config.php';
require_once '../../../classes/student.php';
require_once '../../../classes/image_uploader.php';

$db = new Database();
$conn = $db->connect();

$student = new Student($conn);

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])){
    $id = $_POST['id'];
    $string_image = $_POST['image'];

    if (!empty($string_image)) {     
        // Delete physical files using ImageUploader's deleteFile method
        $uploader = new ImageUploader('../../../assets/images/user/student');
        $uploader->deleteFile($string_image);
    }

    $result = $student->deleteStudent($id);

    //ลบแถวข้อมูลใน Database
    echo json_encode($result); //response
}

exit;


?>