<?php

require 'user.php';

class Alumni extends User
{

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

    public function deleteAlumni($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE user_id = :id");
        $stmt->bindParam(':id', $id);

        $result = $stmt->execute();

        return ['result' => $result, 'message' => $result ? 'ลบข้อมูล ID ' . $id . ' สำเร้็จ' : 'ลบข้อมูลไม่สำเร็จ เกิดข้อผิดพลาดขึ้นกับฐานข้อมูล'];
    }

    public function getTotalCount()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE user_type = 'alumni' AND status_register = 1");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function getAllAlumni($limit, $offset)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE user_type = 'alumni' LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAlumni($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE user_type = 'alumni' AND status_register = 1 AND user_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function editAlumni(
        $id,
        $student_code,
        $first_name,
        $last_name,
        $email,
        $phone,
        $education_level,
        $graduation_year,
        $status_register,
        $current_job,
        $current_company,
        $current_salary,
        $image,
        $status_education,
        $address,
        $facebook,
        $instagram,
        $tiktok,
        $line,
        $password = ''
    ) {
        try {
            $result_check_duplicate = $this->checkDuplicate($id, $student_code, $email, $phone);
            if (!$result_check_duplicate['result']) {
                return $result_check_duplicate;
            }

            // เริ่มสร้างคำสั่ง SQL
            $sql = "UPDATE {$this->table} SET 
                student_code = :student_code,
                first_name = :first_name,
                last_name = :last_name,
                email = :email,
                phone = :phone,
                education_level = :education_level,
                graduation_year = :graduation_year,
                status_register = :status_register,
                current_job = :current_job,
                current_company = :current_company,
                current_salary = :current_salary,
                image = :image,
                status_education = :status_education,
                address = :address,
                facebook = :facebook,
                instagram = :instagram,
                tiktok = :tiktok,
                line = :line";

            // ถ้ามีการส่ง password มา จะอัปเดตด้วย
            if (!empty($password)) {
                $sql .= ", password = :password";
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            }

            $sql .= " WHERE user_id = :id";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':student_code', $student_code);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':education_level', $education_level);
            $stmt->bindParam(':graduation_year', $graduation_year);
            $stmt->bindParam(':status_register', $status_register);
            $stmt->bindParam(':current_job', $current_job);
            $stmt->bindParam(':current_company', $current_company);
            $stmt->bindParam(':current_salary', $current_salary, PDO::PARAM_INT);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':status_education', $status_education);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':facebook', $facebook);
            $stmt->bindParam(':instagram', $instagram);
            $stmt->bindParam(':tiktok', $tiktok);
            $stmt->bindParam(':line', $line);

            if (!empty($password)) {
                $stmt->bindParam(':password', $hashedPassword);
            }

            $stmt->bindParam(':id', $id);

            $result = $stmt->execute();

            return [
                'result' => $result,
                'message' => $result ? 'อัปเดตข้อมูล ID ' . $id . ' สำเร็จ' : 'อัปเดตข้อมูลไม่สำเร็จ เกิดข้อผิดพลาดขึ้นกับฐานข้อมูล'
            ];
        } catch (PDOException $e) {
            return ['result' => false, 'message' => $e->getMessage()];
        }
    }


    //กรองข้อมูล
    public function searchAndFilterAlumni($keyword = '', $education_level = '', $graduation_year = '', $status_register = '', $start_date = '', $end_date = '', $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_type = 'alumni'";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (first_name LIKE :keyword OR last_name LIKE :keyword OR student_code LIKE :keyword)";
            $params[':keyword'] = '%' . $keyword . '%';
        }

        if (!empty($education_level)) {
            $sql .= " AND education_level = :education_level";
            $params[':education_level'] = $education_level;
        }

        if (!empty($graduation_year)) {
            $sql .= " AND graduation_year = :graduation_year";
            $params[':graduation_year'] = (int)$graduation_year;
        }

        if ($status_register !== null && $status_register !== '') {
            $sql .= " AND status_register = :status_register";
            $params[':status_register'] = $status_register;
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
    public function getSearchAndFilterCount($keyword = '', $education_level = '', $graduation_year = '', $status_register = '', $start_date = '', $end_date = '')
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE user_type = 'alumni'";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (first_name LIKE :keyword OR last_name LIKE :keyword OR student_code LIKE :keyword)";
            $params[':keyword'] = '%' . $keyword . '%';
        }

        if (!empty($education_level)) {
            $sql .= " AND education_level = :education_level";
            $params[':education_level'] = $education_level;
        }

        if (!empty($graduation_year)) {
            $sql .= " AND graduation_year = :graduation_year";
            $params[':graduation_year'] = $graduation_year;
        }

        if ($status_register !== null && $status_register !== '') {
            $sql .= " AND status_register = :status_register";
            $params[':status_register'] = $status_register;
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

    public function createAlumniByAdmin($student_code, $first_name, $last_name, $password, $email, $phone, $education_level, $graduation_year, $image = '')
    {
        try {
            $result_check_duplicate = $this->checkDuplicateRegister($student_code, $email, $phone);
            if (!$result_check_duplicate['result']) {
                return $result_check_duplicate;
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            //ตรวจสอบว่ามีการอัพโหลดรูปภาพมาไหม ถ้ามีให้ทำการบันทึกรูปภาพ
            $new_image_files = '';
            if (!empty($image['name'])) {
                $uploader = new ImageUploader('../../../assets/images/user/alumni');
                $uploader->setMaxFileSize(5 * 1024 * 1024) // MAX SIZE 5MB
                    ->setMaxFiles(1); // Limit based on existing images

                $new_image_files .= $first_name . '_' . $last_name;
                $result = $uploader->uploadSingle($_FILES['image'], $new_image_files);
                $new_image_files = $result['fileName'];
            }

            $stmt = $this->conn->prepare("INSERT INTO {$this->table} (student_code, first_name, last_name, password, email, phone, user_type, education_level, status_register, graduation_year, image)
                    VALUES (:student_code, :first_name, :last_name, :password, :email, :phone, 'alumni', :education_level, 1, :graduation_year, :image)");
            $stmt->bindParam(':student_code', $student_code);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':education_level', $education_level);
            $stmt->bindParam(':graduation_year', $graduation_year);
            $stmt->bindParam(':image', $new_image_files);

            if ($stmt->execute()) {
                return ['result' => true, 'message' => 'สร้างบัญชี ศิษย์เก่า สำเร็จ'];
            } else {
                return ['result' => false, 'message' => 'เกิดข้อผิดพลาดในการสร้างบัญชีผู้ใช้'];
            }
        } catch (PDOException $e) {
            return ['result' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
