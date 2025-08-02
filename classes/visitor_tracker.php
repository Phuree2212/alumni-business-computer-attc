<?php
class VisitorTracker
{
    private $conn;
    private $preventSeconds; // เวลาป้องกันการนับซ้ำ (วินาที)
    private $table = 'visitor_logs';

    public function __construct($conn, $preventSeconds = 600)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->conn = $conn;
        $this->preventSeconds = $preventSeconds;
    }

    private function getIP()
    {
        return $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    }

    public function countViewWebsite()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table}");
        $stmt->execute();

        return $stmt->fetchColumn();
    }


    public function track()
    {
        $ip = $this->getIP();
        $page = $_SERVER['REQUEST_URI'] ?? '';
        $visit_time = date("Y-m-d H:i:s");

        // สร้างคีย์ session เฉพาะหน้านั้น
        $sessionKey = 'track_' . md5($page);

        if (!isset($_SESSION[$sessionKey]) || time() - $_SESSION[$sessionKey] > $this->preventSeconds) {
            // ยังไม่เคยเข้า หรือห่างเกินกำหนด
            $stmt = $this->conn->prepare("INSERT INTO {$this->table} (ip_address, visit_time, page_url) VALUES (:ip, :visit_time, :page_url)");
            $stmt->bindParam(':ip', $ip);
            $stmt->bindParam(':visit_time', $visit_time);
            $stmt->bindParam(':page_url', $page);
            $stmt->execute();

            $_SESSION[$sessionKey] = time(); // บันทึกเวลาล่าสุด
        }
    }
}
