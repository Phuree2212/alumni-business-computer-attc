<?php 
require_once '../../auth/auth_all.php';
require_once '../../classes/webboard.php';
require_once '../../classes/image_uploader.php';

$db = new Database();
$conn = $db->connect();
$webboard = new Webboard($conn);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user_id = $_SESSION['user']['id'];
    $user_type = $_SESSION['user']['user_type'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $group_type = $_POST['group_type'];
    $year_group = $group_type == 'year_group' ? $_SESSION['user']['graduation_year'] : 0;

    //จัดการรูปภาพ
    $image_files_name = '';
    if(!empty($_FILES['images'])){
        $uploader = new ImageUploader('../../assets/images/webboard');
        $uploader->setMaxFileSize(5 * 1024 * 1024) // MAX SIZE 5MB
            ->setMaxFiles(5);

        $result = $uploader->uploadMultiple($_FILES['images'], 'topic');

        if ($result['success']) {
            foreach ($result['files'] as $file) {
                $image_files_name .= $file['fileName'] . ',';
            }
            $image_files_name = rtrim($image_files_name, ',');
        }

        if (!empty($result['errors'])) {
            foreach ($result['errors'] as $error) {
                echo "<script>alert('" . $error . "')</script>";
            }
        }
    }

    $result = $webboard->createTopic($user_id, $user_type, $title, $content, $image_files_name, $group_type, $year_group);
    echo json_encode($result);
}


?>