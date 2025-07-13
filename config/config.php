<?php
// เปิดรายงาน error ขณะพัฒนา
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

// ตั้ง timezone
date_default_timezone_set('Asia/Bangkok');

// เรียก constants
require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/database.php';
?>