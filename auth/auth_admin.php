<?php
require_once '../config/config.php';
require_once '../classes/admin.php';

$db = new Database();
$conn = $db->connect();

$admin = new Admin($conn, 'admin');

// ตรวจสอบการเข้าสู่ระบบ
if (!$admin->isLoggedIn()) {
    header('Location: login.php');
    exit;
}