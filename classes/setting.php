<?php 

class Setting {
    private $conn;
    private $table = 'site_settings';

    public function __construct($db){
        $this->conn;
    }

    public function updateSettingSite($site_name, $description, $logo, $icon, $main_website){
    try {
        // อัปเดต site_name
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET setting_value = :site_name WHERE setting_id = 3");
        $stmt->execute(['site_name' => $site_name]);

        // อัปเดต description
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET setting_value = :description WHERE setting_id = 9");
        $stmt->execute(['description' => $description]);

        // อัปเดต logo
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET setting_value = :logo WHERE setting_id = 1");
        $stmt->execute(['logo' => $logo]);

        // อัปเดต icon
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET setting_value = :icon WHERE setting_id = 5");
        $stmt->execute(['icon' => $icon]);

        // ถ้า main_website มี setting_id คนละตัว ต้องเปลี่ยนเป็น id ใหม่ เช่น 7
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET setting_value = :main_website WHERE setting_id = 8");
        $stmt->execute(['main_website' => $main_website]);

        return ["result" => true, "message" => "อัปเดตการตั้งค่าเว็บไซต์สำเร็จ"];
    } catch (PDOException $e) {
        // จัดการ error เช่น log หรือ return false
        return ["result" => false, "message" => "Database Error: " . $e->getMessage()];
    }
}
}

?>