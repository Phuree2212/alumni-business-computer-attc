<?php

class Dashboard
{
    private $conn;

    private $tbl_users = 'users';
    private $tbl_visitor = 'visitor_logs';
    private $tbl_webboard = 'forum_posts';

    function __construct($db)
    {
        $this->conn = $db;
    }

    function getForumTopTen($group_type = '')
    {
        if (!empty($group_type)) {
            $stmt = $this->conn->prepare("SELECT p.*, u.first_name, u.last_name, COUNT(DISTINCT l.post_id) AS count_like, COUNT(DISTINCT c.comment_id) AS count_comment
                                          FROM {$this->tbl_webboard} AS p 
                                          LEFT JOIN post_likes AS l ON p.post_id = l.post_id
                                          LEFT JOIN forum_comments AS c ON p.post_id = c.post_id
                                          LEFT JOIN users AS u ON p.user_id = u.user_id
                                          WHERE group_type = :group_type
                                          GROUP BY p.post_id
                                          ORDER BY count_like DESC
                                          LIMIT 10");
            $stmt->bindParam(':group_type', $group_type);
        } else {
            $stmt = $this->conn->prepare("SELECT p.*, u.first_name, u.last_name, COUNT(DISTINCT l.post_id) AS count_like, COUNT(DISTINCT c.comment_id) AS count_comment
                                          FROM {$this->tbl_webboard} AS p 
                                          LEFT JOIN post_likes AS l ON p.post_id = l.post_id
                                          LEFT JOIN forum_comments AS c ON p.post_id = c.post_id
                                          LEFT JOIN users AS u ON p.user_id = u.user_id
                                          GROUP BY p.post_id
                                          ORDER BY count_like DESC
                                          LIMIT 10");
        }


        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getUserType()
    {
        $stmt = $this->conn->prepare("SELECT user_type, COUNT(*) AS total FROM {$this->tbl_users} GROUP BY user_type");
        $stmt->execute();
        $results = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[$row['user_type']] = $row['total'];
        }

        return $results;
    }

    function getAlumniYear($education_level = '', $graduation_year = '', $status_education = '')
    {
        $sql = "SELECT graduation_year, COUNT(*) AS total 
            FROM {$this->tbl_users} 
            WHERE user_type = 'alumni'";
        $params = [];

        if ($education_level !== '') {
            $sql .= " AND education_level = :education_level";
            $params[':education_level'] = $education_level;
        }
        if ($graduation_year !== '') {
            $sql .= " AND graduation_year = :graduation_year";
            $params[':graduation_year'] = $graduation_year;
        }
        if ($status_education !== '') {
            $sql .= " AND status_education = :status_education";
            $params[':status_education'] = $status_education;
        }

        $sql .= " GROUP BY graduation_year";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[$row['graduation_year']] = $row['total'];
        }

        return $results;
    }

    function getAlumniEducationLevel($education_level = '', $graduation_year = '', $status_education = '')
    {
        $sql = "SELECT education_level, COUNT(*) AS total 
            FROM {$this->tbl_users} 
            WHERE user_type = 'alumni'";
        $params = [];

        if ($education_level !== '') {
            $sql .= " AND education_level = :education_level";
            $params[':education_level'] = $education_level;
        }
        if ($graduation_year !== '') {
            $sql .= " AND graduation_year = :graduation_year";
            $params[':graduation_year'] = $graduation_year;
        }
        if ($status_education !== '') {
            $sql .= " AND status_education = :status_education";
            $params[':status_education'] = $status_education;
        }

        $sql .= " GROUP BY education_level";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[$row['education_level']] = $row['total'];
        }

        return $results;
    }

    function getAlumniStatusEducation($education_level = '', $graduation_year = '', $status_education = '')
    {
        $sql = "SELECT status_education, COUNT(*) AS total 
            FROM {$this->tbl_users} 
            WHERE user_type = 'alumni'";
        $params = [];

        if ($education_level !== '') {
            $sql .= " AND education_level = :education_level";
            $params[':education_level'] = $education_level;
        }
        if ($graduation_year !== '') {
            $sql .= " AND graduation_year = :graduation_year";
            $params[':graduation_year'] = $graduation_year;
        }
        if ($status_education !== '') {
            $sql .= " AND status_education = :status_education";
            $params[':status_education'] = $status_education;
        }

        $sql .= " GROUP BY status_education";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $key = empty($row['status_education']) ? 'ไม่มีข้อมูล' : $row['status_education'];
            $results[$key] = $row['total'];
        }

        return $results;
    }


    public function getViewWebsite($groupBy = 'day', $start = null, $end = null)
    {
        $groupField = '';
        $labelField = '';
        $whereClause = '';
        $params = [];

        switch (strtolower($groupBy)) {
            case 'month':
                $groupField = "YEAR(visit_time), MONTH(visit_time)";
                $labelField = "DATE_FORMAT(visit_time, '%Y-%m')";
                if ($start && $end) {
                    $whereClause = "WHERE DATE_FORMAT(visit_time, '%Y-%m') BETWEEN :start AND :end";
                    $params[':start'] = $start;
                    $params[':end'] = $end;
                }
                break;
            case 'year':
                $groupField = "YEAR(visit_time)";
                $labelField = "YEAR(visit_time)";
                if ($start && $end) {
                    $whereClause = "WHERE YEAR(visit_time) BETWEEN :start AND :end";
                    $params[':start'] = (int)$start;
                    $params[':end'] = (int)$end;
                }
                break;
            case 'day':
            default:
                $groupField = "DATE(visit_time)";
                $labelField = "DATE(visit_time)";
                if ($start && $end) {
                    $whereClause = "WHERE DATE(visit_time) BETWEEN :start AND :end";
                    $params[':start'] = $start;
                    $params[':end'] = $end;
                }
                break;
        }

        $sql = "SELECT {$labelField} AS visit_at, COUNT(*) AS count_view
                FROM {$this->tbl_visitor}
                {$whereClause}
                GROUP BY {$groupField}
                ORDER BY visit_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[$row['visit_at']] = (int)$row['count_view'];
        }

        return $results;
    }
}
