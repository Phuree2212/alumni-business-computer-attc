<?php 
class Register{
    private $conn;
    private $table = 'user';

    public function __construct($db){
        $this->conn;
    }

    // ตรวจสอบว่าอีเมลมีอยู่แล้วหรือยัง
    public function isEmailExists($email) {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function register($student_code, $first_name, $last_name, $password, $email, $phone, $user_type, $education_level, $graduation_year){
        try{
            if($this->isEmailExists($email)){
                return ['result' => false, 'message' => 'Email ถูกใช้งานแล้ว'];
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $this->conn->prepare("INSERT INTO {$this->table} (student_code, first_name, lastname, password, email, phone, user_type, education_level, graduation_year)
                    VALUES (:student_code, :first_name, :lastname, :password, :email, :phone, :user_type, :education_level, :graduation_year)");        
            $stmt->bindParam(':student_conde', $student_code);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':user_type', $user_type);
            $stmt->bindParam(':education_level', $education_level);
            $stmt->bindParam(':graduation_year', $graduation_year);

            if($stmt->execute()){
                return ['result' => true, 'message' => 'ลงทะเบียนสำเร็จ'];
            } else {
                return ['result' => false, 'message' => 'เกิดข้อผิดพลาดในการลงทะเบียน'];
            }
        }
        catch(PDOException $e){
            return ['result' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

}

?>