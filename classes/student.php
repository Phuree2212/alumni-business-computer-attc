<?php

require 'user.php';

class Student extends User
{

    public function getStudent($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE user_type = 'student' AND status_register = 1 AND user_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkDuplicate($id, $student_code = '', $email = '', $phone = '')
    {
        if (!empty($student_code)) {
            $stmt = $this->conn->prepare("SELECT COUNT(user_id) FROM {$this->table} WHERE student_code = :student_code AND user_id != :user_id");
            $stmt->bindParam(':student_code', $student_code);
            $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);

            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                return ['result' => false, 'message' => 'รหัสนักเรียนนี้ถูกใช้แล้ว'];
            }
        }

        if (!empty($email)) {
            $stmt = $this->conn->prepare("SELECT COUNT(user_id) FROM {$this->table} WHERE email = :email AND user_id != :user_id");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);

            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                return ['result' => false, 'message' => 'อีเมลนี้ถูกใช้แล้ว'];
            }
        }

        if (!empty($phone)) {
            $stmt = $this->conn->prepare("SELECT COUNT(user_id) FROM {$this->table} WHERE phone = :phone AND user_id != :user_id");
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);

            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                return ['result' => false, 'message' => 'เบอร์โทรศัพท์นี้ถูกใช้แล้ว'];
            }
        }

        return ['result' => true, 'message' => 'ไม่พบข้อมูลซ้ำในระบบ'];; // ไม่มีข้อมูลซ้ำ
    }

    public function deleteStudent($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE user_id = :id");
        $stmt->bindParam(':id', $id);

        $result = $stmt->execute();

        return ['result' => $result, 'message' => $result ? 'ลบข้อมูล ID ' . $id . ' สำเร้็จ' : 'ลบข้อมูลไม่สำเร็จ เกิดข้อผิดพลาดขึ้นกับฐานข้อมูล'];
    }


    public function getTotalCount()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE user_type = 'student' AND status_register = 1");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function getAllStudent($limit, $offset)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE user_type = 'student' AND status_register = 1 LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function editStudent($id, $student_code, $first_name, $last_name, $email, $phone, $education_level, $status_register, $image, $address, $facebook, $instagram, $line, $tiktok)
    {
        $result_check_duplicate = $this->checkDuplicate($id,$student_code, $email, $phone);
        if(!$result_check_duplicate['result']){
            return $result_check_duplicate;
        }

        $stmt = $this->conn->prepare("UPDATE {$this->table} SET student_code = :student_code, first_name = :first_name, last_name = :last_name, email = :email, phone = :phone, education_level = :education_level, status_register = :status_register
                                      ,image = :image, address = :address, facebook = :facebook, instagram = :instagram, line = :line, tiktok = :tiktok WHERE user_id = :id");

        $stmt->bindParam(':student_code', $student_code);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':education_level', $education_level);
        $stmt->bindParam(':status_register', $status_register);

        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':facebook', $facebook);
        $stmt->bindParam(':instagram', $instagram);
        $stmt->bindParam(':line', $line);
        $stmt->bindParam(':tiktok', $tiktok);

        $stmt->bindParam(':id', $id);

        $result = $stmt->execute();

        return ['result' => $result, 'message' => $result ? 'อัปเดตข้อมูล ID ' . $id . ' สำเร้็จ' : 'อัปเดตข้อมูลไม่สำเร็จ เกิดข้อผิดพลาดขึ้นกับฐานข้อมูล'];
    }

    //กรองข้อมูล
    public function searchAndFilterStudent($keyword = '', $education_level = '', $start_date = '', $end_date = '', $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_type = 'student' AND status_register = 1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (first_name LIKE :keyword OR last_name LIKE :keyword OR student_code LIKE :keyword)";
            $params[':keyword'] = '%' . $keyword . '%';
        }

        if (!empty($education_level)) {
            $sql .= " AND education_level = :education_level";
            $params[':education_level'] = $education_level;
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

    // นับการกรองข้อมูล
    public function getSearchAndFilterCount($keyword = '', $education_level = 'ปวส.1', $start_date = '', $end_date = '')
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE user_type = 'student' AND status_register = 1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (first_name LIKE :keyword OR last_name LIKE :keyword OR student_code LIKE :keyword)";
            $params[':keyword'] = '%' . $keyword . '%';
        }

        if (!empty($education_level)) {
            $sql .= " AND education_level = :education_level";
            $params[':education_level'] = $education_level;
        }

        if (!empty($start_date)) {
            $sql .= " AND DATE(created_at) >= :start_date";
            $params[':start_date'] = $start_date;
        }

        if (!empty($end_date)) {
            $sql .= " AND DATE(created_at) <= :end_date";
            $params[':end_date'] = $end_date;
        }

        $stmt = $this->conn->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
