<?php
require 'pagination_helper.php';

class Admin
{
    protected $conn;
    protected $table = 'admin';
    private $role;
    private $path_manage_image;

    public function __construct($db, $role)
    {
        $this->conn = $db;
        $this->role = $role;
        $this->path_manage_image = $this->role == 'admin' ? 'admin' : 'teacher';
    }

    // ตรวจสอบการซ้ำของการสร้างบัญชีผู้ดูแลระบบใหม่
    public function checkDuplicateCreate($username = '', $email = '', $phone = '')
    {
        if (!empty($username)) {
            $stmt = $this->conn->prepare("SELECT COUNT(admin_id) FROM {$this->table} WHERE username = :username ");
            $stmt->bindParam(':username', $username);

            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                return ['result' => false, 'message' => 'ชื่อผู้ใช้นี้ถูกใช้ในระบบแล้ว'];
            }
        }

        if (!empty($email)) {
            $stmt = $this->conn->prepare("SELECT COUNT(admin_id) FROM {$this->table} WHERE email = :email");
            $stmt->bindParam(':email', $email);

            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                return ['result' => false, 'message' => 'อีเมลนี้ถูกใช้แล้ว'];
            }
        }

        if (!empty($phone)) {
            $stmt = $this->conn->prepare("SELECT COUNT(admin_id) FROM {$this->table} WHERE phone = :phone");
            $stmt->bindParam(':phone', $phone);

            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                return ['result' => false, 'message' => 'เบอร์โทรศัพท์นี้ถูกใช้แล้ว'];
            }
        }

        return ['result' => true, 'message' => 'ไม่พบข้อมูลซ้ำในระบบ'];; // ไม่มีข้อมูลซ้ำ
    }

    public function create($username, $password, $email, $first_name, $last_name, $phone, $position, $image = '')
    {
        try {
            $result_check_duplicate = $this->checkDuplicateCreate($username, $email, $phone);
            if (!$result_check_duplicate['result']) {
                return $result_check_duplicate;
            }

            //เข้ารหัสรหัสผ่าน
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            //ตรวจสอบว่ามีการอัพโหลดรูปภาพมาไหม ถ้ามีให้ทำการบันทึกรูปภาพ
            $new_image_files = '';
            if (!empty($image['name'])) {
                $uploader = new ImageUploader('../../../assets/images/user/'. $this->path_manage_image);
                $uploader->setMaxFileSize(5 * 1024 * 1024) // MAX SIZE 5MB
                    ->setMaxFiles(1); // Limit based on existing images

                $new_image_files .= $first_name . '_' . $last_name;
                $result = $uploader->uploadSingle($_FILES['image'], $new_image_files);
                $new_image_files = $result['fileName'];
            }

            $stmt = $this->conn->prepare("INSERT INTO {$this->table} (username, password, email, first_name, last_name, phone, position, role, image)
                    VALUES (:username, :password, :email, :first_name, :last_name, :phone, :position, :role, :image)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':position', $position);
            $stmt->bindParam(':role', $this->role);
            $stmt->bindParam(':image', $new_image_files);

            if ($stmt->execute()) {
                return ['result' => true, 'message' => 'สร้างบัญชีผู้ดูแลระบบใหม่สำเร็จ'];
            } else {
                // ดึง error info จาก PDO และแสดงรายละเอียด
                $errorInfo = $stmt->errorInfo();
                return [
                    'result' => false,
                    'message' => 'สร้างบัญชีผู้ดูแลระบบใหม่ไม่สำเร็จ เนื่องจาก: ' . $errorInfo[2] // index 2 คือข้อความ error จริง
                ];
            }
        } catch (PDOException $e) {
            return ['result' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function isLoggedIn(){
        return isset($_SESSION['user']) && ($_SESSION['user']['role'] === USER_TYPE_ADMIN || $_SESSION['user']['role'] === USER_TYPE_TEACHER);
    }
    //เข้าสู่ระบบ
    public function login($username, $password)
    {
        try {
            $stmt = $this->conn->prepare("SELECT admin_id, username, first_name, last_name, password FROM {$this->table} WHERE username = :username LIMIT 1");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $id = $user['admin_id'];
                $first_name = $user['first_name'];
                $last_name = $user['last_name'];
                $password_hashed = $user['password'];

                //ตรวจสอบรหัสผ่านแบบ BCRYPT
                if (password_verify($password, $password_hashed)) {

                    $_SESSION['user'] = ['id' => $id,
                                         'username' => $username,
                                         'role' => $this->role == 'admin' ? USER_TYPE_ADMIN : USER_TYPE_TEACHER,
                                         'fullname' => $first_name . ' ' . $last_name];

                    return ['result' => true, 'message' => 'เข้าสู่ระบบสำเร็จ'];
                } else {
                    return ['result' => false, 'message' => 'ชื่อผู้ใช้ หรือ รหัสผ่านไม่ถูกต้อง'];
                }
            } else {
                return ['result' => false, 'message' => 'ชื่อผู้ใช้ หรือ รหัสผ่านไม่ถูกต้อง'];
            }
        } catch (PDOException $e) {
            return ['result' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    //ตรวจสอบค่าซ้ำตอนอัปเดตข้อมูล
    public function checkDuplicate($id, $username = '', $email = '', $phone = '')
    {
        if (!empty($username)) {
            $stmt = $this->conn->prepare("SELECT COUNT(admin_id) FROM {$this->table} WHERE username = :username AND admin_id != :admin_id");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':admin_id', $id, PDO::PARAM_INT);

            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                return ['result' => false, 'message' => 'ชื่อผู้ใช้นี้ถูกใช้แล้ว'];
            }
        }

        if (!empty($email)) {
            $stmt = $this->conn->prepare("SELECT COUNT(admin_id) FROM {$this->table} WHERE email = :email AND admin_id != :admin_id");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':admin_id', $id, PDO::PARAM_INT);

            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                return ['result' => false, 'message' => 'อีเมลนี้ถูกใช้แล้ว'];
            }
        }

        if (!empty($phone)) {
            $stmt = $this->conn->prepare("SELECT COUNT(admin_id) FROM {$this->table} WHERE phone = :phone AND admin_id != :admin_id");
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':admin_id', $id, PDO::PARAM_INT);

            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                return ['result' => false, 'message' => 'เบอร์โทรศัพท์นี้ถูกใช้แล้ว'];
            }
        }

        return ['result' => true, 'message' => 'ไม่พบข้อมูลซ้ำในระบบ'];; // ไม่มีข้อมูลซ้ำ
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE admin_id = :id");
        $stmt->bindParam(':id', $id);

        $result = $stmt->execute();

        return ['result' => $result, 'message' => $result ? 'ลบข้อมูล ID ' . $id . ' สำเร้็จ' : 'ลบข้อมูลไม่สำเร็จ เกิดข้อผิดพลาดขึ้นกับฐานข้อมูล'];
    }


    public function getTotalCount()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE role = :role");
        $stmt->bindParam(':role', $this->role);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function getAll($limit, $offset)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE role = :role LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function edit($id, $username, $email, $first_name, $last_name, $phone, $position, $image = '')
    {
        $result_check_duplicate = $this->checkDuplicate($id, $username, $email, $phone);
        if (!$result_check_duplicate['result']) {
            return $result_check_duplicate;
        }

        $stmt = $this->conn->prepare("UPDATE {$this->table} SET username = :username, email = :email, first_name = :first_name, last_name = :last_name, phone = :phone,
                                     position = :position, image = :image 
                                     WHERE admin_id = :id");

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':image', $image);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $result = $stmt->execute();

        return ['result' => $result, 'message' => $result ? 'อัปเดตข้อมูล ID ' . $id . ' สำเร้็จ' : 'อัปเดตข้อมูลไม่สำเร็จ เกิดข้อผิดพลาดขึ้นกับฐานข้อมูล'];
    }

    //กรองข้อมูล
    public function searchAndFilter($keyword = '', $start_date = '', $end_date = '', $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE role = :role";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (first_name LIKE :keyword OR last_name LIKE :keyword OR username LIKE :keyword OR admin_id LIKE :keyword)";
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

        $stmt->bindValue(':role', $this->role);
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
    public function getSearchAndFilterCount($keyword = '', $start_date = '', $end_date = '')
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE role = :role";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (first_name LIKE :keyword OR last_name LIKE :keyword OR username LIKE :keyword OR admin_id LIKE :keyword)";
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

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':role', $this->role);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
