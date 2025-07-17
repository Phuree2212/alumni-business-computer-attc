<?php 
function thaiDateFormat($datetime) {
    // แปลงเป็น timestamp
    $timestamp = strtotime($datetime);

    // ตรวจสอบว่าแปลงได้ไหม
    if (!$timestamp) return '-';

    // รายชื่อเดือนภาษาไทย
    $thai_months = [
        "01" => "มกราคม", "02" => "กุมภาพันธ์", "03" => "มีนาคม",
        "04" => "เมษายน", "05" => "พฤษภาคม", "06" => "มิถุนายน",
        "07" => "กรกฎาคม", "08" => "สิงหาคม", "09" => "กันยายน",
        "10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม"
    ];

    // แยกส่วนวัน เดือน ปี
    $day = date("d", $timestamp);
    $month = date("m", $timestamp);
    $year = date("Y", $timestamp) + 543;

    // คืนค่าวันที่รูปแบบภาษาไทย
    return intval($day) . " " . $thai_months[$month] . " " . $year;
}

?>