<?php
require_once '../config/config.php';
require_once '../classes/user.php';

$db = new Database();
$conn = $db->connect();

$user = new User($conn);

// ตรวจสอบการเข้าสู่ระบบ
if (!$admin->isLoggedIn()) {
    header('Location: login.php');
    exit;
}