<?php 
require_once '../config/config.php';

$locate_link = $base_url;

if(isset($_SESSION['user']['user_type']) && ($_SESSION['user']['user_type'] == USER_TYPE_ALUMNI || $_SESSION['user']['user_type'] == USER_TYPE_STUDENT)){
    $locate_link .= '/public/login.php';
}

if(isset($_SESSION['user']['user_type']) && ($_SESSION['user']['user_type'] == USER_TYPE_ADMIN || $_SESSION['user']['user_type'] == USER_TYPE_TEACHER)){
    $locate_link .= '/admin/login.php';
}

session_unset();
session_destroy();

header('Location: ' . $locate_link);

exit;

?>