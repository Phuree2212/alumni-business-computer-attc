<?php

//Class จัดการข้อมูลข่าวสาร
class Activities
{
    private $conn;
    private $table = "activities";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function countViewActivity($activity_id)
    {
        $session_key = "viewed_activity_" . $activity_id;
        $current_time = time(); // เวลาปัจจุบัน (หน่วยเป็นวินาที)
        $cooldown = 600; // 600 วินาที = 10 นาที

        // ถ้ายังไม่เคยดู หรือ ดูเกิน 10 นาทีแล้ว
        if (!isset($_SESSION[$session_key]) || ($current_time - $_SESSION[$session_key]) > $cooldown) {
            $stmt = $this->conn->prepare("UPDATE {$this->table} SET views_count = views_count + 1 WHERE activity_id = :id");
            $stmt->bindParam(':id', $activity_id, PDO::PARAM_INT);
            $stmt->execute();

            // บันทึกเวลาที่ดูล่าสุด
            $_SESSION[$session_key] = $current_time;
        }
    }

    //ดึงจำนวนทั้งหมดจากฐานข้อมูล
    public function getTotalCount()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    //ดึงจำนวนข้อมูลจากกรองข้อมูล
    public function getSearchAndFilterCount($keyword = '', $start_date = '', $end_date = '')
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (title LIKE :keyword OR content LIKE :keyword)";
            $params[':keyword'] = '%' . $keyword . '%';
        }

        if (!empty($start_date)) {
            $sql .= " AND DATE(created_at) >= :start_date";
            $params[':start_date'] = $start_date;
        }

        if (!empty($end_date)) {
            $sql .= " AND DATE(created_at) <= :end_date";
            $params[':end_date'] = $end_date;
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    //ดึงข้อมูลข่าวสารทั้งหมด
    public function getActivity($activity_id)
    {
        $stmt = $this->conn->prepare("SELECT ac.*, a.first_name, a.last_name, a.image as image_profile, a.role FROM {$this->table} as ac 
                                      INNER JOIN admin as a
                                      ON ac.created_by = a.admin_id 
                                      WHERE activity_id = :id");

        $stmt->bindParam(':id', $activity_id);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //ดึงข้อมูลข่าวสารทั้งหมด
    public function getAllActivity($limit = null, $offset = null)
    {
        if (isset($limit) && isset($offset)) {
            $stmt = $this->conn->prepare("SELECT ac.*, a.first_name, a.last_name FROM {$this->table} AS ac
                                          LEFT JOIN admin as a ON ac.created_by = a.admin_id 
                                          ORDER BY created_at DESC 
                                          LIMIT :limit OFFSET :offset");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //สร้างข่าวสารใหม่
    public function createActivity($title, $content, $date, $image, $created_by)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (title, content, date, image, created_by)
                VALUES (:title, :content, :date, :image, :created_by)");

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':created_by', $created_by, PDO::PARAM_INT);

        return $stmt->execute();
    }

    //แก้ไขข้อมูลข่าวสาร
    public function editActivity($id, $title, $date, $content, $image)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET title = :title, content = :content, date = :date, image = :image WHERE activity_id = :id");

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    //ลบข้อมูลข่าวสาร
    public function deleteActivity($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE activity_id = :id");
        $stmt->bindParam(':id', $id);

        $result = $stmt->execute();

        return ['result' => $result, 'message' => $result ? 'ลบข้อมูล ID ' . $id . ' สำเร้็จ' : 'ลบข้อมูลไม่สำเร็จ เกิดข้อผิดพลาดขึ้นกับฐานข้อมูล'];
    }

    //กรองข้อมูล
    public function searchAndFilterActivity($keyword = '', $start_date = '', $end_date = '', $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (title LIKE :keyword OR content LIKE :keyword)";
            $params[':keyword'] = '%' . $keyword . '%';
        }

        if (!empty($start_date)) {
            $sql .= " AND DATE(created_at) >= :start_date";
            $params[':start_date'] = $start_date;
        }

        if (!empty($end_date)) {
            $sql .= " AND DATE(created_at) <= :end_date";
            $params[':end_date'] = $end_date;
        }

        $sql .= " ORDER BY created_at DESC";

        if (is_numeric($limit) && is_numeric($offset)) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->conn->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        if (is_numeric($limit) && is_numeric($offset)) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllImageActivity($limit = null, $offset = null)
    {
        if (isset($limit) && isset($offset)) {
            $stmt = $this->conn->prepare("SELECT image FROM {$this->table} ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        } else {
            $stmt = $this->conn->prepare("SELECT image FROM {$this->table} ORDER BY created_at DESC");
        }

        $stmt->execute();
        $array_image = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'image');
        return $array_image;
    }
}
