<?php
class User
{
    protected $conn;
    protected $table = 'users';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function countUser()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table}");
        $stmt->execute();

        return $stmt->fetchColumn();
    }
    
    public function checkStatusRegisterUser($data)
    {
        $sql = "SELECT status_register FROM {$this->table} WHERE";
        $data_explode = explode(" ", $data);
        if(count($data_explode) > 1){
            $first_name = $data_explode[0];
            $last_name = $data_explode[1];
            $sql .= " first_name = :first_name AND last_name = :last_name";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
        }else{
            $student_code = $data;
            $sql .= " student_code = :student_code";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':student_code', $data);
        }

        $stmt->execute();
        $user = $stmt->fetchColumn();
        
        if ($user > 0) {
            if ($user == 0) {
                return ['result' => false, 'message' => 'สถานะการลงทะเบียนของท่าน ไม่ได้รับการอณุญาติให้ใช้งาน หากมีข้อสงสัยกรุณาติดต่อเจ้าหน้าที่ที่เกี่ยวข้อง'];
            }
            if ($user == 1) {
                return ['result' => true, 'message' => 'สถานะการลงทะเบียนของท่าน ได้รับการอณุญาติให้ใช้งาน ท่านสามารถเข้าสู่ระบบได้'];
            }
            if ($user == 2) {
                return ['result' => false, 'message' => 'สถานะการลงทะเบียนของท่าน อยู่ในระหว่างการตรวจสอบ หากคำขอของท่านยังไม่ได้รับการอณุญาติ กรุณาติดต่อเจ้าหน้าที่ที่เกี่ยวข้อง'];
            }
        }else{
            return ['result' => false, 'message' => 'ไม่พบ รหัสนักศึกษา หรือ ชื่อ นามสกุล ของท่านที่ลงทะเบียนไว้ในระบบ'];
        }
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
            if (!$result_check_duplicate['result']) {
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
            $stmt = $this->conn->prepare("SELECT user_id, password, status_register, user_type, first_name, last_name, education_level, graduation_year, image FROM users WHERE student_code = :student_code LIMIT 1");
            $stmt->bindParam(':student_code', $student_code);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                $id = $user['user_id'];
                $user_type = $user['user_type'];
                $first_name = $user['first_name'];
                $last_name = $user['last_name'];
                $education_level = $user['education_level'];
                $graduation_year = $user['graduation_year'];
                $image = $user['image'];

                $password_hashed = $user['password'];
                $status_register = $user['status_register'];

                //ตรวจสอบรหัสผ่านแบบ BCRYPT
                if (password_verify($password, $password_hashed)) {

                    //ตรวจสอบสถานะการสมัครสมาชิก
                    if (intval($status_register == 0)) {
                        return ['result' => false, 'message' => 'บัญชีของท่านไม่ได้รับการอนุมัติให้เข้าใช้งาน กรุณาติดต่อเจ้าหน้าที่ที่เกี่ยวข้อง'];
                    }
                    if (intval($status_register != 1)) {
                        return ['result' => false, 'message' => 'บัญชีของท่านกำลังอยู่ในระหว่างการตรวจสอบจากผู้ดูแลระบบ ท่านสามารถตรวจสอบผลการลงทะเบียนของท่านได้ผ่านทาง Email ของท่าน'];
                    }

                    //สร้าง session เมื่อเข้าสู่ระบบได้สำเร็จ
                    $_SESSION['user'] = [
                        'id' => $id,
                        'student_code' => $student_code,
                        'user_type' => $user_type == 'alumni' ? USER_TYPE_ALUMNI : USER_TYPE_STUDENT,
                        'fullname' => $first_name . ' ' . $last_name,
                        'education_level' => $education_level,
                        'graduation_year' => $graduation_year,
                        'image' => $image,
                    ];


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
