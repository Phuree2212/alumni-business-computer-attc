<?php 
require_once '../config/config.php';

session_unset();
session_destroy();

header('Location: ' . $base_url . '/admin/login.php');

exit;

?>