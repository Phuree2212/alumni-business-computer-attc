<?php 
require_once '../../auth/auth_user.php';
require_once '../../classes/webboard.php';

$db = new Database();
$conn = $db->connect();
$comment = new CommentForum($conn);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user']['id'];
    $user_type = $_SESSION['user']['user_type'];
    $content = $_POST['comment'];
    $parent_comment_id = !empty($_POST['parent_comment_id']) ? $_POST['parent_comment_id'] : 0;

    $result = $comment->createCommentPost($post_id,$user_id, $user_type, $content, $parent_comment_id);
    
    echo json_encode($result);
}


?>