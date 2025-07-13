<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/auth.php';

$auth = new Auth();

// ตรวจสอบการเข้าสู่ระบบ
if (!$auth->isLoggedInUser()) {
    header('Location:' . $base_url . '/public/login.php');
    exit;
}