<?php

class Setting
{
    private $conn;
    private $table = 'site_settings';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function updateSettingSite($site_name = '', $description = '', $logo = '', $icon = '', $main_website = '', $banner = '', $admin_email = '')
    {
        try {
            // site_name
            if ($site_name !== '') {
                $stmt = $this->conn->prepare("UPDATE {$this->table} SET setting_value = :site_name WHERE setting_id = 3");
                $stmt->execute(['site_name' => $site_name]);
            }

            // description
            if ($description !== '') {
                $stmt = $this->conn->prepare("UPDATE {$this->table} SET setting_value = :description WHERE setting_id = 9");
                $stmt->execute(['description' => $description]);
            }

            // logo
            if ($logo !== '') {
                $stmt = $this->conn->prepare("UPDATE {$this->table} SET setting_value = :logo WHERE setting_id = 1");
                $stmt->execute(['logo' => $logo]);
            }

            // icon
            if ($icon !== '') {
                $stmt = $this->conn->prepare("UPDATE {$this->table} SET setting_value = :icon WHERE setting_id = 5");
                $stmt->execute(['icon' => $icon]);
            }

            // main_website
            if ($main_website !== '') {
                $stmt = $this->conn->prepare("UPDATE {$this->table} SET setting_value = :main_website WHERE setting_id = 8");
                $stmt->execute(['main_website' => $main_website]);
            }

            if ($banner !== '') {
                $stmt = $this->conn->prepare("UPDATE {$this->table} SET setting_value = :banner WHERE setting_id = 2");
                $stmt->execute(['banner' => $banner]);
            }

            if ($admin_email !== '') {
                $stmt = $this->conn->prepare("UPDATE {$this->table} SET setting_value = :admin_email WHERE setting_id = 4");
                $stmt->execute(['admin_email' => $admin_email]);
            }

            return ["result" => true, "message" => "อัปเดตการตั้งค่าเว็บไซต์สำเร็จ"];
        } catch (PDOException $e) {
            return ["result" => false, "message" => "Database Error: " . $e->getMessage()];
        }
    }


    public function getValueSettingSite()
    {
        try {
            // อัปเดต site_name
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table}");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // แปลง array ให้ key เป็น setting_key
            $assoc = [];
            foreach ($result as $row) {
                $assoc[$row['setting_key']] = $row['setting_value']; // หรือทั้งแถว $row ถ้าต้องการข้อมูลมากกว่าค่า value
            }

            return $assoc;
        } catch (PDOException $e) {
            // จัดการ error เช่น log หรือ return false
            return ["result" => false, "message" => "Database Error: " . $e->getMessage()];
        }
    }
}
