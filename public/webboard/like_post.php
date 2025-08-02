<?php 
require_once '../../auth/auth_user.php';
require_once '../../classes/webboard.php';

$db = new Database();
$conn = $db->connect();
$like_post = new LikeForum($conn);

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])){
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user']['id'];
    $user_type = $_SESSION['user']['user_type'];

    //like
    if($_POST['action'] == 'like'){
        $result = $like_post->likePost($post_id,$user_id, $user_type);
        echo json_encode($result);
    }
    //unlike
    if($_POST['action'] == 'unlike'){
        $result = $like_post->unLikePost($post_id,$user_id, $user_type);
        echo json_encode($result);
    }
    exit;
}


?>