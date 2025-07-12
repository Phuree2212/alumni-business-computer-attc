<?php 

require_once '../../../config/config.php';
require_once '../../../classes/user.php';
header('Content-Type: application/json');

$db = new Database();
$conn = $db->connect();

$user_approval = new UserApproval($conn);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = $_POST['id'];

    $result = $user_approval->approveUser($id);

    if($result['result']){
        echo json_encode($result);
    }else{
        echo json_encode($result);
    }
}

exit;



?>