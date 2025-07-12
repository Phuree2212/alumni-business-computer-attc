<?php

//Class จัดการข้อมูลข่าวสาร
class News
{
    private $conn;
    private $table = "news";

    public function __construct($db)
    {
        $this->conn = $db;
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
    public function getNews($news_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE news_id = :id");

        $stmt->bindParam(':id', $news_id);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //ดึงข้อมูลข่าวสารทั้งหมด
    public function getAllNews($limit = null, $offset = null)
    {
        if (isset($limit) && isset($offset)) {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //สร้างข่าวสารใหม่
    public function createNews($title, $content, $image, $created_by)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (title, content, image, created_by)
                VALUES (:title, :content, :image, :created_by)");

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':created_by', $created_by);

        return $stmt->execute();
    }

    //แก้ไขข้อมูลข่าวสาร
    public function editNews($id, $title, $content, $image)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET title = :title, content = :content, image = :image WHERE news_id = :id");

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    //ลบข้อมูลข่าวสาร
    public function deleteNews($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE news_id = :id");
        $stmt->bindParam(':id', $id);

        $result = $stmt->execute();
        
        return ['result' => $result, 'message' => $result ? 'ลบข้อมูล ID ' . $id . ' สำเร้็จ' : 'ลบข้อมูลไม่สำเร็จ เกิดข้อผิดพลาดขึ้นกับฐานข้อมูล'];
    }

    //กรองข้อมูล
    public function searchAndFilterNews($keyword = '', $start_date = '', $end_date = '', $limit = null, $offset = null) {
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

}
