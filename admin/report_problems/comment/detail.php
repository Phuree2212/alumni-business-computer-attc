<?php
require_once '../../../auth/auth_admin.php';
require_once '../../../classes/webboard.php';
require_once '../../../classes/image_uploader.php';

$db = new Database();
$conn = $db->connect();

$report = new ReportForum($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    //ดึงรายละเอียดข้อมูลข่าวสาร
    $item = $report->getCommentReportProblem($id);
    if ($item) {

        $id = $item['report_id'];
        $reason = $item['reason'];
        $content = $item['content'];

        $post_id = $item['post_id'];
        $comment_id = $item['comment_id'];
        $user_comment_id = $item['post_by_id'];
        $comment_by = $item['post_first_name'] . ' ' . $item['post_last_name'];

        $post_student_code = $item['post_student_code'];
        $post_user_type = $item['post_user_type'];

        $user_report_id = $item['report_by_id'];
        $report_by = $item['report_first_name'] . ' ' . $item['report_last_name'];
        $reported_at = date('d/m/Y H:i', strtotime($item['reported_at']));
    } else {
        echo "<script>alert('ไม่พบข้อมูลการรายงาน')</script>";
        echo "<script>window.location.href='index.php'</script>";
    }
} else {
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <?php include '../../../includes/title.php'; ?>
    <link href="../../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../../../assets/css/style_admin.css" rel="stylesheet">
    <link href="../../../assets/css/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- TinyMCE Editor -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
</head>


<body>

    <?php include '../../includes/sidebar.php' ?>

    <div class="main-content">
        <div class="card-body">
            <h3 class="h3 mb-4 text-danger">รายละเอียดการรายงานความคิดเห็นจากผู้ใช้งาน</h3>

            <h5 class="mb-2">ข้อมูลการรายงาน</h5>
            <div class="mb-3">
                <label class="form-label fw-bold">วันที่รายงาน<span class="text-danger"> : </span></label>
                <span class="info-value"><?php echo $reported_at ?></span>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">ID ผู้รายงาน<span class="text-danger"> : </span></label>
                <span class="info-value"><?php echo $user_report_id ?></span>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">ผู้รายงาน<span class="text-danger"> : </span></label>
                <span class="info-value"><?php echo $report_by ?></span>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">ความเห็นผู้รายงาน<span class="text-danger"> : </span></label>
                <span class="info-value"><?php echo $reason ?></span>
            </div>
            <hr>

            <div class="d-flex gap-2 align-items-center mb-2">
                <h5>ข้อมูลความคิดเห็น</h5>
                <a class="btn btn-primary" target="_blank" href="../../../public/webboard/topic.php?id=<?php echo $post_id ?>">ดูโพสต์</a>
            </div>


            <div class="mb-3">
                <label class="form-label fw-bold">ID กระทู้<span class="text-danger"> : </span></label>
                <span class="info-value"><?php echo $post_id ?></span>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">ID ผู้โพสต์กระทู้<span class="text-danger"> : </span></label>
                <span class="info-value"><?php echo $user_comment_id ?></span> <a href="../../user/<?php echo $post_user_type ?>/?keyword=<?php echo $post_student_code ?>">ดูข้อมูลผู้โพสต์</a>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">ผู้โพสต์กระทู้<span class="text-danger"> : </span></label>
                <span class="info-value"><?php echo $comment_by ?></span>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">ข้อความความคิดเห็น<span class="text-danger"> : </span></label>
                <span class="info-value"><?php echo $content ?></span>
            </div>

            <hr>


            <div class="mt-3">
                <button onclick="deleteReport(<?php echo $id ?>)" class="btn btn-success">ไม่พบการใช้งานที่ไม่เหมาะสม</button>
                <button onclick="deleteData(<?php echo $comment_id ?>, '<?php echo 'id=' . urlencode($comment_id) ?>', 'delete_comment.php')" class="btn btn-danger">ลบความคิดเห็น</button>
            </div>

            <div class="mt-3">
                <a href="index.php" class="btn btn-secondary">กลับ</a>
            </div>

        </div>
    </div>

    <script src="../../../assets/js/bootstrap.bundle.js"></script>
    <script src="../../../assets/js/sweetalert2.all.min.js"></script>
    <script src="../../../assets/js/script_admin.js"></script>
    <script src="../../../assets/alerts/modal.js"></script>
    <script src="../../functions/delete_data.js"></script>
    <script src="../../functions/tinymce.js"></script>
    <script>
        function deleteReport(id) {
            return modalConfirm("ยืนยันไม่พบการใช้งานที่ไม่เหมาะสมของกระทู้?", "ข้อมูลการรายงานจะถูกลบยืนยันการกระทำใช้หรือไม่")
                .then((result) => {
                    if (result.isConfirmed) {
                        fetch('delete.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: 'id=' + id
                            })
                            .then(response => response.json())
                            .then(response => {
                                if (response.result) {
                                    modalAlert(`ลบข้อมูล ID ที่ ${id} สำเร็จ`, "ข้อมูลได้ถูกลบเรียบร้อยแล้ว", "success")
                                        .then(() => {
                                            window.location.href = 'index.php';
                                        });
                                } else {
                                    modalAlert(`เกิดข้อผิดพลาด`, "มีบางอย่างผิดพลาด กรุณาลองใหม่อีกครั้ง", "error")
                                }
                            })
                            .catch(error => {
                                modalAlert(`การเชื่อมต่อล้มเหลว`, "ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้", "error")
                                console.error('Fetch error:', error);
                            });
                    }
                });
        }
    </script>
</body>

</html>