<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ฟังก์ชันตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// ฟังก์ชันตรวจสอบว่าผู้ใช้งานเป็นแอดมินหรือไม่
function is_admin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === USER_TYPE_ADMIN;
}

// redirect ถ้ายังไม่ได้ login
function require_login() {
    if (!is_logged_in()) {
        header("Location: " . BASE_URL . "login.php");
        exit;
    }
}

?>