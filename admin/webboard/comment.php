<?php
require_once '../../auth/auth_admin.php';
require_once '../../classes/webboard.php';
require_once '../../classes/pagination_helper.php';

$db = new Database();
$conn = $db->connect();
$webboard = new CommentForum($conn);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $post_id = $_GET['id'];
    $comment_post = $webboard->getCommentPost($post_id, 1);
}

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <?php include '../../includes/title.php'; ?>>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../../assets/css/style_admin.css" rel="stylesheet">
    <link href="../../assets/css/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

</head>
<style>
    .search-filter-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        padding-left: 2.5rem;
        border-radius: 10px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .search-box .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .filter-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .table img {
        max-width: 200px;
        min-width: 100px;
    }
</style>

<body>

    <?php include '../includes/sidebar.php' ?>

    <div class="main-content">
        <!-- Heading -->
        <div class="d-flex flex-column mb-5">
            <h3 class="table-header"><b>จัดการข้อมูลความคิดเห็น กระทู้ ID ที่ <?php echo $post_id ?></b></h3>
            <h5>ความคิดเห็น <?php echo count($comment_post); ?> รายการ</h5>
        </div>


        <!-- Tables List -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ผู้แสดงความคิดเห็น</th>
                            <th>ความคิดเห็น</th>
                            <th>วันที่แสดงความคิดเห็น</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (!empty($comment_post)) {
                            $i = 1;
                            foreach ($comment_post as $item) {
                                $comment_id = $item['comment_id'];
                                $user_comment = $item['first_name'] . ' ' . $item['last_name'];
                                $created_at = $item['created_at'];
                                $content_comment = $item['content'];
                                $user_id = $item['user_id'];
                        ?>
                                <tr>
                                    <td>
                                        <?= $i++; ?>
                                    </td>
                                    <td><?= $user_comment ?></td>
                                    <td><?= $content_comment ?></td>
                                    <td><?= $created_at ?></td>
                                    <td class="text-center">
                                        <button class="action-btn btn-outline-danger" onclick="deleteData(<?php echo $comment_id ?>, 'comment_id=<?php echo $comment_id  ?>', 'delete_comment.php')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                        <?php }
                        } ?>


                    </tbody>
                </table>
                <!--ภ้าไม่มีข้อมูลให้แสดงคำว่า ไม่พบข้อมูลข่าวสาร -->
                <?php if (empty($comment_post)) { ?>
                    <div class="text-center text-danger">ไม่พบข้อมูลความคิดเห็น</div>
                <?php } ?>

            </div>
        </div>

    </div>



    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="../../assets/js/script_admin.js"></script>
    <script src="../../assets/js/sweetalert2.all.min.js"></script>
    <script src="../../assets/alerts/modal.js"></script>
    <script src="../functions/delete_data.js"></script>
</body>

</html>