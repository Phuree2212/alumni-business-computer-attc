<?php

class Webboard
{
    protected $conn;
    private $table = 'forum_posts';

    function __construct($db)
    {
        $this->conn = $db;
    }

    //topic
    public function getTotalCount()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function searchAndFilterForum($keyword = '', $start_date = '', $end_date = '', $year_group = 0, $limit = null, $offset = null)
    {
        $sql = "SELECT f.*, u.image as profile, u.first_name, u.last_name, 
                   COUNT(DISTINCT l.like_id) AS like_count, 
                   COUNT(DISTINCT c.comment_id) AS comment_count
            FROM {$this->table} as f
            LEFT JOIN users as u ON f.user_id = u.user_id
            LEFT JOIN post_likes as l ON f.post_id = l.post_id
            LEFT JOIN forum_comments as c ON f.post_id = c.post_id
            WHERE 1";

        $params = [];

        if (true) {
            $sql .= " AND (f.year_group = :year_group)";
            $params[':year_group'] = $year_group;
        }

        if (!empty($keyword)) {
            $sql .= " AND (f.title LIKE :keyword OR f.content LIKE :keyword)";
            $params[':keyword'] = '%' . $keyword . '%';
        }

        if (!empty($start_date)) {
            $sql .= " AND DATE(f.created_at) >= :start_date";
            $params[':start_date'] = $start_date;
        }

        if (!empty($end_date)) {
            $sql .= " AND DATE(f.created_at) <= :end_date";
            $params[':end_date'] = $end_date;
        }

        $sql .= " GROUP BY f.post_id";
        $sql .= " ORDER BY f.created_at DESC";

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


    public function getSearchAndFilterCount($keyword = '', $start_date = '', $end_date = '', $year_group = 0,)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1";
        $params = [];

        if (true) {
            $sql .= " AND (year_group = :year_group)";
            $params[':year_group'] = $year_group;
        }

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

    function getAllTopic($limit = "", $offset = "")
    {
        $stmt = $this->conn->prepare("SELECT f.*, u.image as profile, u.first_name, u.last_name, COUNT(DISTINCT l.like_id) AS like_count, COUNT(DISTINCT c.comment_id) AS comment_count FROM {$this->table} as f 
                                      LEFT JOIN users as u ON f.user_id = u.user_id 
                                      LEFT JOIN post_likes as l ON f.post_id = l.post_id
                                      LEFT JOIN forum_comments as c ON f.post_id = c.post_id
                                      GROUP BY f.post_id  
                                      ORDER BY created_at DESC 
                                      LIMIT :limit OFFSET :offset");

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getTopicMe($user_id, $user_type, $limit = "", $offset = "")
    {
        $stmt = $this->conn->prepare("SELECT f.*, u.image as profile, u.first_name, u.last_name, COUNT(DISTINCT l.like_id) AS like_count, COUNT(DISTINCT c.comment_id) AS comment_count FROM {$this->table} as f 
                                      LEFT JOIN users as u ON f.user_id = u.user_id 
                                      LEFT JOIN post_likes as l ON f.post_id = l.post_id
                                      LEFT JOIN forum_comments as c ON f.post_id = c.post_id
                                      WHERE f.user_id = :user_id AND f.user_type = :user_type
                                      GROUP BY f.post_id   
                                      ORDER BY created_at DESC 
                                      LIMIT :limit OFFSET :offset");

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_type', $user_type, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function countTopicMe($user_id, $user_type)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE user_id = :user_id AND user_type = :user_type");

        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_type', $user_type, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    function getTopic($id)
    {
        $stmt = $this->conn->prepare("SELECT f.*, u.image AS profile, u.first_name, u.last_name, COUNT(l.post_id) AS like_count FROM {$this->table} as f 
                                      LEFT JOIN users as u ON f.user_id = u.user_id 
                                      LEFT JOIN post_likes as l ON f.post_id = l.post_id
                                      WHERE f.post_id = :id
                                      GROUP BY f.post_id");
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function createTopic($user_id, $user_type, $title, $content, $image, $group_type, $year_group = 0)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (user_id, user_type, title, content, image, group_type, year_group) 
                                      VALUES (:user_id, :user_type, :title, :content, :image, :group_type, :year_group)");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_type', $user_type, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':group_type', $group_type);
        $stmt->bindParam(':year_group', $year_group, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return ['result' => true, 'message' => 'สร้างกระทู้ใหม่สำเร็จ'];
        } else {
            return ['result' => false, 'message' => 'สร้างกระทู้ไม่สำเร็จ เกิดข้อผิดพลาดในการสร้างข้อมูล'];
        }
    }

    public function deleteTopic($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE post_id = :id");
        $stmt->bindParam(':id', $id);

        $result = $stmt->execute();

        return ['result' => $result, 'message' => $result ? 'ลบข้อมูล ID ' . $id . ' สำเร้็จ' : 'ลบข้อมูลไม่สำเร็จ เกิดข้อผิดพลาดขึ้นกับฐานข้อมูล'];
    }
}

class CommentForum extends Webboard
{
    private $table = 'forum_comments';
    //comment
    function createCommentPost($post_id, $user_id, $user_type, $content, $parent_comment_id = 0)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (post_id, user_id, user_type, content, parent_comment_id) 
                                      VALUES (:post_id, :user_id, :user_type, :content, :parent_comment_id)");

        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_type', $user_type, PDO::PARAM_INT);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':parent_comment_id', $parent_comment_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return ['result' => true, 'message' => 'แสดงความคิดเห็นสำเร็จ'];
        } else {
            return ['result' => false, 'message' => 'เกิดข้อผิดพลาดขึ้นในการแสดงความคิดเห็น'];
        }
    }

    function getCommentPost($post_id)
    {
        $stmt = $this->conn->prepare("SELECT c.*, u.user_id, u.first_name, u.last_name, u.image FROM {$this->table} as c 
                                      INNER JOIN users as u ON c.user_id = u.user_id WHERE post_id = :id AND parent_comment_id = 0");

        $stmt->execute([':id' => $post_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getReplyComment($post_id, $comment_id)
    {
        $stmt = $this->conn->prepare("SELECT c.*, u.user_id, u.first_name, u.last_name, u.image FROM {$this->table} as c 
                                      INNER JOIN users as u ON c.user_id = u.user_id WHERE post_id = :id AND parent_comment_id = :comment_id");

        $stmt->execute([
            ':id' => $post_id,
            ':comment_id' => $comment_id
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function deleteComment($comment_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE comment_id = :comment_id OR parent_comment_id = :comment_id");

        $stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return ['result' => true, 'message' => 'ลบความคิดเห็นสำเร็จ'];
        } else {
            return ['result' => false, 'message' => 'ลบความคิดเห็นไม่สำเร็จ เกิดข้อผิดพลาดขึ้น'];
        }
    }

    public function getTotalCountComment()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}

class LikeForum extends Webboard
{
    private $table = 'post_likes';

    function likePost($post_id, $user_id, $user_type)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (post_id, user_id, user_type) 
                                      VALUES (:post_id, :user_id, :user_type)");

        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_type', $user_type, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return ['result' => true, 'message' => 'ถูกใจกระทู้สำเร็จ'];
        } else {
            return ['result' => false, 'message' => 'เกิดข้อผิดพลาดขึ้นในการถูกใจกระทู้'];
        }
    }

    function checkLikePost($post_id, $user_id, $user_type)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE post_id = :post_id AND user_id = :user_id AND user_type = :user_type");

        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_type', $user_type, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchColumn();

        return $result > 0 ? true : false;
    }

    function unLikePost($post_id, $user_id, $user_type)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE post_id = :post_id AND user_id = :user_id AND user_type = :user_type");

            $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_type', $user_type, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return ['result' => true, 'message' => 'ยกเลิกการถูกใจสำเร็จ'];
            } else {
                return ['result' => false, 'message' => 'เกิดข้อผิดพลาดขึ้นในการยกเลิกถูกใจกระทู้'];
            }
        } catch (PDOException $e) {
            return ['result' => false, 'message' => 'error db :' . $e->getMessage()];
        }
    }
}

class ReportForum extends Webboard
{
    private $table_post_report = 'post_reports';
    private $table_comment_report = 'comment_reports';

    function getTotalCountCommentReport()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table_comment_report}");

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    function getAllCommentReportProblem($limit, $offset)
    {
        $stmt = $this->conn->prepare("SELECT  
        r.report_id, 
        r.reason, 
        r.reported_at, 
        f.*, 
        u.user_id as report_by_id, 
        u.first_name as report_first_name, 
        u.last_name as report_last_name,
        a.user_id as post_by_id,
        a.first_name as post_first_name,
        a.last_name as post_last_name
            FROM {$this->table_comment_report} as r 
            INNER JOIN users as u ON r.user_id = u.user_id                    -- ผู้รายงาน
            INNER JOIN forum_comments as f ON r.comment_id = f.comment_id     -- คอมเม้นที่ถูกรายงาน
            INNER JOIN users as a ON f.user_id = a.user_id                    -- เจ้าของโพสต์
            INNER JOIN forum_posts as p ON f.post_id = p.post_id
            ORDER BY r.reported_at DESC
            LIMIT :limit OFFSET :offset");

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getCommentReportProblem($report_id)
    {
        $stmt = $this->conn->prepare("SELECT 
        r.report_id, 
        r.reason, 
        r.reported_at, 
        f.*, 
        u.user_id as report_by_id, 
        u.first_name as report_first_name, 
        u.last_name as report_last_name,
        a.user_id as post_by_id,
        a.first_name as post_first_name,
        a.last_name as post_last_name,
        a.user_type as post_user_type,
        a.student_code as post_student_code
            FROM {$this->table_comment_report} as r 
            INNER JOIN users as u ON r.user_id = u.user_id                    -- ผู้รายงาน
            INNER JOIN forum_comments as f ON r.comment_id = f.comment_id     -- คอมเม้นที่ถูกรายงาน
            INNER JOIN users as a ON f.user_id = a.user_id                    -- เจ้าของโพสต์
            INNER JOIN forum_posts as p ON f.post_id = p.post_id                 
            WHERE report_id = :report_id");

        $stmt->bindValue(':report_id', $report_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getTotalCountTopicReport()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table_post_report}");

        $stmt->execute();

        return $stmt->fetchColumn();
    }
    function getAllTopicReportProblem($limit, $offset)
    {
        $stmt = $this->conn->prepare("SELECT 
        r.status_check, 
        r.report_id, 
        r.reason, 
        r.reported_at, 
        f.*, 
        u.user_id as report_by_id, 
        u.first_name as report_first_name, 
        u.last_name as report_last_name,
        a.user_id as post_by_id,
        a.first_name as post_first_name,
        a.last_name as post_last_name
            FROM {$this->table_post_report} as r 
            INNER JOIN users as u ON r.user_id = u.user_id                     -- ผู้รายงาน
            INNER JOIN forum_posts as f ON r.post_id = f.post_id              -- โพสต์ที่ถูกรายงาน
            INNER JOIN users as a ON f.user_id = a.user_id                    -- เจ้าของโพสต์
            ORDER BY r.reported_at DESC
            LIMIT :limit OFFSET :offset");

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getTopicReportProblem($report_id)
    {
        $stmt = $this->conn->prepare("SELECT r.status_check, 
        r.report_id, 
        r.reason, 
        r.reported_at, 
        f.*, 
        u.user_id as report_by_id, 
        u.first_name as report_first_name, 
        u.last_name as report_last_name,
        a.user_id as post_by_id,
        a.first_name as post_first_name,
        a.last_name as post_last_name,
        a.user_type as post_user_type,
        a.student_code as post_student_code
            FROM {$this->table_post_report} as r 
            INNER JOIN users as u ON r.user_id = u.user_id                     -- ผู้รายงาน
            INNER JOIN forum_posts as f ON r.post_id = f.post_id              -- โพสต์ที่ถูกรายงาน
            INNER JOIN users as a ON f.user_id = a.user_id                    -- เจ้าของโพสต์
            WHERE report_id = :report_id");

        $stmt->bindValue(':report_id', $report_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteReportTopic($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table_post_report} WHERE report_id = :id");
        $stmt->bindParam(':id', $id);

        $result = $stmt->execute();

        return ['result' => $result, 'message' => $result ? 'ลบข้อมูล ID ' . $id . ' สำเร้็จ' : 'ลบข้อมูลไม่สำเร็จ เกิดข้อผิดพลาดขึ้นกับฐานข้อมูล'];
    }

    public function deleteReportComment($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table_comment_report} WHERE report_id = :id");
        $stmt->bindParam(':id', $id);

        $result = $stmt->execute();

        return ['result' => $result, 'message' => $result ? 'ลบข้อมูล ID ' . $id . ' สำเร้็จ' : 'ลบข้อมูลไม่สำเร็จ เกิดข้อผิดพลาดขึ้นกับฐานข้อมูล'];
    }

    function reportPost($post_id, $user_id, $user_type, $reason)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table_post_report} (post_id, user_id, user_type, reason) 
                                      VALUES (:post_id, :user_id, :user_type, :reason)");

        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_type', $user_type, PDO::PARAM_INT);
        $stmt->bindParam(':reason', $reason);

        if ($stmt->execute()) {
            return ['result' => true, 'message' => 'รายงานกระทู้สำเร็จ'];
        } else {
            return ['result' => false, 'message' => 'เกิดข้อผิดพลาดขึ้นในการรายงานกระทู้'];
        }
    }

    function reportComment($comment_id, $user_id, $user_type, $reason)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table_comment_report} (comment_id, user_id, user_type, reason) 
                                      VALUES (:comment_id, :user_id, :user_type, :reason)");

        $stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_type', $user_type, PDO::PARAM_INT);
        $stmt->bindParam(':reason', $reason);

        if ($stmt->execute()) {
            return ['result' => true, 'message' => 'รายงานความคิดเห็นสำเร็จ'];
        } else {
            return ['result' => false, 'message' => 'เกิดข้อผิดพลาดขึ้นในการรายงานความคิดเห็น'];
        }
    }
}
