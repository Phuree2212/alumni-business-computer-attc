<?php
class User
{
    protected $conn;
    protected $table = 'users';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ตรวจสอบว่าอีเมลมีอยู่แล้วหรือยัง
    public function checkDuplicateRegister($student_code = '', $email = '', $phone = '')
    {
        if (!empty($student_code)) {
            $stmt = $this->conn->prepare("SELECT COUNT(user_id) FROM {$this->table} WHERE student_code = :student_code ");
            $stmt->bindParam(':student_code', $student_code);

            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                return ['result' => false, 'message' => 'รหัสนักเรียนนี้ถูกใช้แล้ว'];
            }
        }

        if (!empty($email)) {
            $stmt = $this->conn->prepare("SELECT COUNT(user_id) FROM {$this->table} WHERE email = :email");
            $stmt->bindParam(':email', $email);

            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                return ['result' => false, 'message' => 'อีเมลนี้ถูกใช้แล้ว'];
            }
        }

        if (!empty($phone)) {
            $stmt = $this->conn->prepare("SELECT COUNT(user_id) FROM {$this->table} WHERE phone = :phone");
            $stmt->bindParam(':phone', $phone);

            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                return ['result' => false, 'message' => 'เบอร์โทรศัพท์นี้ถูกใช้แล้ว'];
            }
        }

        return ['result' => true, 'message' => 'ไม่พบข้อมูลซ้ำในระบบ'];; // ไม่มีข้อมูลซ้ำ
    }

    public function register($student_code, $first_name, $last_name, $password, $email, $phone, $user_type, $education_level, $graduation_year)
    {
        try {
            $result_check_duplicate = $this->checkDuplicateRegister($student_code, $email, $phone);
            if(!$result_check_duplicate['result']){
                return $result_check_duplicate;
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $this->conn->prepare("INSERT INTO {$this->table} (student_code, first_name, last_name, password, email, phone, user_type, education_level, graduation_year, status_register)
                    VALUES (:student_code, :first_name, :last_name, :password, :email, :phone, :user_type, :education_level, :graduation_year, 2)");
            $stmt->bindParam(':student_code', $student_code);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':user_type', $user_type);
            $stmt->bindParam(':education_level', $education_level);
            $stmt->bindParam(':graduation_year', $graduation_year);

            if ($stmt->execute()) {
                return ['result' => true, 'message' => 'ลงทะเบียนสำเร็จ'];
            } else {
                return ['result' => false, 'message' => 'เกิดข้อผิดพลาดในการลงทะเบียน'];
            }
        } catch (PDOException $e) {
            return ['result' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function login($student_code, $password)
    {
        try {
            $stmt = $this->conn->prepare("SELECT password, status_register FROM users WHERE student_code = :student_code LIMIT 1");
            $stmt->bindParam(':student_code', $student_code);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $password_hashed = $user['password'];
                $status_register = $user['status_register'];

                //ตรวจสอบรหัสผ่านแบบ BCRYPT
                if (password_verify($password, $password_hashed)) {

                    //ตรวจสอบสถานะการสมัครสมาชิก
                    if (intval($status_register != 1)) {
                        return ['result' => false, 'message' => 'บัญชีของท่านกำลังอยู่ในระหว่างการตรวจสอบจากผู้ดูแลระบบ ท่านสามารถตรวจสอบผลการลงทะเบียนของท่านได้ผ่านทาง Email ของท่าน'];
                    }

                    /*
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['user_type'] = $user['user_type'];*/

                    return ['result' => true, 'message' => 'เข้าสู่ระบบสำเร็จ'];
                } else {
                    return ['result' => false, 'message' => 'รหัสนักศึกษา หรือ รหัสผ่านไม่ถูกต้อง'];
                }
            } else {
                return ['result' => false, 'message' => 'รหัสนักศึกษา หรือ รหัสผ่านไม่ถูกต้อง'];
            }
        } catch (PDOException $e) {
            return ['result' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}

class UserApproval extends User
{
    //นับจำนวนผู้ใช้ที่ยังไม่ผ่านการตรวจสอบ
    public function getTotalCount()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE status_register = 2");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // ดึงรายชื่อผู้ใช้ที่ยังรออนุมัติ (status_register = 2)
    public function getRegisterWaiingApprove($limit, $offset)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE status_register = 2 LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // อนุมัติผู้ใช้ (เปลี่ยน status_register เป็น 1 หรือสถานะที่ระบุ)
    public function approveUser($userId)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET status_register = 1 WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $result = $stmt->execute();
        return ['result' => $result, 'message' => $result ? 'อนุมัติบัญชีผู้ใช้สำเร็จ' : 'อนุมัติบัญชีผู้ใช้ไม่สำเร็จ'];
    }

    // ปฏิเสธผู้ใช้ (เปลี่ยนสถานะเป็น 0 หรือลบข้อมูลก็ได้)
    public function rejectUser($userId)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET status_register = 0 WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $result = $stmt->execute();
        return ['result' => $result, 'message' => $result ? 'ปฏิเสธการอนุมัติบัญชีผู้ใช้สำเร็จ' : 'ปฏิเสธการอนุมัติบัญชีผู้ใช้ไม่สำเร็จ'];
    }

    //กรองข้อมูล
    public function searchAndFilterUserRegister($keyword = '', $user_type = '', $start_date = '', $end_date = '', $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (first_name LIKE :keyword OR last_name LIKE :keyword OR student_code LIKE :keyword)";
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

        if (!empty($user_type)) {
            $sql .= " AND user_type = :user_type";
            $params[':user_type'] = $user_type;
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
    public function getSearchAndFilterCount($keyword = '', $user_type = '', $start_date = '', $end_date = '')
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (first_name LIKE :keyword OR last_name LIKE :keyword OR student_code LIKE :keyword)";
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

        if (!empty($user_type)) {
            $sql .= " AND user_type = :user_type";
            $params[':user_type'] = $user_type;
        }

        $stmt = $this->conn->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
