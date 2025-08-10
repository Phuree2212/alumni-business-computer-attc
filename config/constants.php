<?php

// Site
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http');
$base_url .= '://' . $_SERVER['HTTP_HOST'];
$base_url .= '/alumni_business_computer_attc'; // ถ้าอยู่ในโฟลเดอร์นี้บน localhost

require_once __DIR__ . '/../classes/setting.php'; //นำเข้าไฟล์ class setting.php

$db = new Database();
$conn = $db->connect();
$setting = new Setting($conn);
$setting_value = $setting->getValueSettingSite();

define('BASE_URL', $base_url);
define('SITE_NAME', $setting_value['site_name']);
define('PATH_LOGO', $base_url . '/assets/images/logo/' . $setting_value['logo']);
define('BANNERS', $setting_value['banner']);
define('PATH_ICON', $base_url . '/assets/images/logo/' . $setting_value['favicon'] );

// User Type
define('USER_TYPE_STUDENT', 1);
define('USER_TYPE_ALUMNI', 2);
define('USER_TYPE_ADMIN', 3);
define('USER_TYPE_TEACHER', 4);

// Group Type
define('GROUP_PUBLIC', 1);
define('GROUP_SAME_YEAR', 2);

//Status Report Problem
define('STATUS_REPORT_NONE', 0);        // ไม่พบการใช้งานที่ไม่เหมาะสมของกระทู้นี้
define('STATUS_REPORT_FOUND', 1);       // พบว่ามีการโพสต์ที่ไม่เหมาะสม
define('STATUS_REPORT_PENDING', 2);     // รอดำเนินการตรวจสอบ

?>
