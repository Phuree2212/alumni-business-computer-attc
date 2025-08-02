<?php 
require_once '../../auth/auth_user.php';
require_once '../../classes/webboard.php';

$db = new Database();
$conn = $db->connect();
$report = new ReportForum($conn);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $user_id = $_SESSION['user']['id'];
    $user_type = $_SESSION['user']['user_type'];
    

    if(!empty($_POST['comment_id'])){
        $comment_id = $_POST['comment_id'];
        $reason = $_POST['reason'];
        echo json_encode($report->reportComment($comment_id, $user_id, $user_type, $reason));
    }else{
        $post_id = $_POST['post_id'];
        $reason = $_POST['reason'];
        echo json_encode($report->reportPost($post_id, $user_id, $user_type, $reason));
    }
    
    exit;
}


?>