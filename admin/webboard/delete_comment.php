<?php 
require_once '../../auth/auth_admin.php';
require_once '../../classes/webboard.php';

$db = new Database();
$conn = $db->connect();
$comment = new CommentForum($conn);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $comment_id = $_POST['comment_id'];

    $result = $comment->deleteComment($comment_id);
    
    echo json_encode($result);
}


?>