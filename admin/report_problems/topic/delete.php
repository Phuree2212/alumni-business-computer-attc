<?php
require_once '../../../auth/auth_admin.php'; 
require_once '../../../classes/webboard.php';

$db = new Database();
$conn = $db->connect();

$webboard = new ReportForum($conn);

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])){
    $id = $_POST['id'];

    //ลบแถวข้อมูลใน Database
    $result = $webboard->deleteReportTopic($id);
    
    echo json_encode($result); //response
}

?>