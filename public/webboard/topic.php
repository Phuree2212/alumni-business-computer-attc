<?php
require_once '../../auth/auth_all.php';
require_once '../../classes/webboard.php';
require_once '../../config/function.php';

$db = new Database();
$conn = $db->connect();
$webboard = new Webboard($conn);
$comment = new CommentForum($conn);
$like = new LikeForum($conn);
$report = new ReportForum($conn);

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    //topic_details
    $id = (int)$_GET['id'];

    if(!$topic_detail = $webboard->getTopic($id)){
        echo "<script>alert('ไม่พบข้อมูลกระทู้')</script>";
        echo "<script>window.location.href='index.php'</script>";
        exit;
    }

    $title = $topic_detail['title'];
    $content = $topic_detail['content'];
    $string_image = $topic_detail['image'];
    $images = explode(',', $topic_detail['image']);
    $created_at = $topic_detail['created_at'];
    $time_only = date("H:i", strtotime($created_at));
    $like_count = $topic_detail['like_count'];

    //user_detail_post
    $user_id = $topic_detail['user_id'];
    $fullname = $topic_detail['first_name'] . ' ' . $topic_detail['last_name'];
    $image_profile = $topic_detail['profile'];
    $user_type = $topic_detail['user_type'];

    $user_path = $topic_detail['user_type'] == USER_TYPE_ALUMNI ? 'alumni' : ($topic_detail['user_type'] == USER_TYPE_STUDENT ? 'student' : ($topic_detail['user_type'] == USER_TYPE_ADMIN ? 'admin' : 'teacher'));

    //comment_list
    $comment_list = $comment->getCommentPost($id);

    $is_topic_user = ($user_id == $_SESSION['user']['id'] && $user_type == $_SESSION['user']['user_type']);

    //check_like_forum
    $check_like_post = $like->checkLikePost($id, $_SESSION['user']['id'], $_SESSION['user']['user_type']);
} else {
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <?php include '../../includes/title.php' ?>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../../assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/sweetalert2.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
</head>

<style>
    .card {
        background: #fff;
        transition: .5s;
        border: 0;
        margin-bottom: 30px;
        border-radius: .55rem;
        position: relative;
        width: 100%;
        box-shadow: 0 1px 2px 0 rgb(0 0 0 / 10%);
    }

    .card .body {
        color: #444;
        padding: 20px;
        font-weight: 400;
    }

    .card .header {
        color: #444;
        padding: 20px;
        position: relative;
        box-shadow: none;
    }

    .single_post {
        -webkit-transition: all .4s ease;
        transition: all .4s ease
    }

    .single_post .body {
        padding: 30px
    }

    .single_post .img-post {
        position: relative;
        overflow: hidden;
        width: 100%;
        margin-bottom: 30px
    }

    .single_post .img-post>img {
        width: 100%;
    }

    .single_post .img-post:hover .social_share {
        display: block
    }

    .single_post .footer {
        padding: 0 30px 30px 30px
    }

    .single_post .footer .actions {
        display: inline-block
    }

    .single_post .footer .stats {
        cursor: default;
        list-style: none;
        padding: 0;
        display: inline-block;
        float: right;
        margin: 0;
        line-height: 35px
    }

    .single_post .footer .stats li {
        border-left: solid 1px rgba(160, 160, 160, 0.3);
        display: inline-block;
        font-weight: 400;
        letter-spacing: 0.25em;
        line-height: 1;
        margin: 0 0 0 2em;
        padding: 0 0 0 2em;
        text-transform: uppercase;
        font-size: 13px
    }

    .single_post .footer .stats li a {
        color: #777
    }

    .single_post .footer .stats li:first-child {
        border-left: 0;
        margin-left: 0;
        padding-left: 0
    }

    .single_post h3 {
        font-size: 20px;
        text-transform: uppercase
    }

    .single_post h3 a {
        color: #242424;
        text-decoration: none
    }

    .single_post p {
        font-size: 16px;
        line-height: 26px;
        font-weight: 300;
        margin: 0
    }

    .single_post .blockquote p {
        margin-top: 0 !important
    }

    .single_post .meta {
        list-style: none;
        padding: 0;
        margin: 0
    }

    .single_post .meta li {
        display: inline-block;
        margin-right: 15px
    }

    .single_post .meta li a {
        font-style: italic;
        color: #959595;
        text-decoration: none;
        font-size: 12px
    }

    .single_post .meta li a i {
        margin-right: 6px;
        font-size: 12px
    }

    .single_post2 {
        overflow: hidden
    }

    .single_post2 .content {
        margin-top: 15px;
        margin-bottom: 15px;
        padding-left: 80px;
        position: relative
    }

    .single_post2 .content .actions_sidebar {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 60px
    }

    .single_post2 .content .actions_sidebar a {
        display: inline-block;
        width: 100%;
        height: 60px;
        line-height: 60px;
        margin-right: 0;
        text-align: center;
        border-right: 1px solid #e4eaec
    }

    .single_post2 .content .title {
        font-weight: 100
    }

    .single_post2 .content .text {
        font-size: 15px
    }

    .right-box .categories-clouds li {
        display: inline-block;
        margin-bottom: 5px
    }

    .right-box .categories-clouds li a {
        display: block;
        border: 1px solid;
        padding: 6px 10px;
        border-radius: 3px
    }

    .right-box .instagram-plugin {
        overflow: hidden
    }

    .right-box .instagram-plugin li {
        float: left;
        overflow: hidden;
        border: 1px solid #fff
    }

    .comment-reply li {
        margin-bottom: 15px
    }

    .comment-reply li:last-child {
        margin-bottom: none
    }

    .comment-reply li h5 {
        font-size: 18px
    }

    .comment-reply li p {
        margin-bottom: 0px;
        font-size: 15px;
        color: #777
    }

    .comment-reply .list-inline li {
        display: inline-block;
        margin: 0;
        padding-right: 20px
    }

    .comment-reply .list-inline li a {
        font-size: 13px
    }

    .post-author {
        margin-bottom: 1rem;
    }

    .avatar img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .media-body label {
        font-weight: 500;
        margin-bottom: 0.25rem;
        display: block;
        color: #212529;
    }

    .media-body span {
        font-size: 0.875rem;
        color: #6c757d;
    }

    @media (max-width: 640px) {
        .blog-page .left-box .single-comment-box>ul>li {
            padding: 25px 0
        }

        .blog-page .left-box .single-comment-box ul li .icon-box {
            display: inline-block
        }

        .blog-page .left-box .single-comment-box ul li .text-box {
            display: block;
            padding-left: 0;
            margin-top: 10px
        }

        .blog-page .single_post .footer .stats {
            float: none;
            margin-top: 10px
        }

        .blog-page .single_post .body,
        .blog-page .single_post .footer {
            padding: 30px
        }
    }
</style>

<body>
    <?php include '../../includes/navbar.php' ?>

    <div id="main-content" class="blog-page">
        <div class="container mt-5">
            <div class="row clearfix">
                <div class="left-box">
                    <div class="card single_post">
                        <div class="body">
                            <?php if (!empty($images[0])) { ?>
                                <?php foreach ($images as $image) { ?>
                                    <div class="img-post">
                                        <img class="d-block img-fluid" src="../../assets/images/webboard/<?php echo $image ?>">
                                    </div>
                            <?php }
                            } ?>

                            <div class="post-author">

                                <div class="d-flex">
                                    <div class="dropdown position-absolute end-0">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="background: transparent; border: none; box-shadow: none;">
                                            <i class="fas fa-ellipsis-v" style="font-size: 1rem; color: black;"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#reportModal">รายงานปัญหากระทู้</a></li>
                                            <?php if ($is_topic_user || $_SESSION['user']['user_type'] == USER_TYPE_ADMIN) { ?>
                                                <li><a class="dropdown-item text-danger" id="DeleteComment" onclick="deleteTopic(<?php echo $id ?>, '<?php echo $string_image ?>')">ลบกระทู้</a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <div class="avatar me-3">
                                        <img src="<?php
                                                    echo !empty($image_profile)
                                                        ? '../../assets/images/user/' .
                                                        $user_path .
                                                        '/' . $image_profile
                                                        : '../../assets/images/user/no-image-profile.jpg';
                                                    ?>" alt="" title="">
                                    </div>
                                    <div class="media-body">
                                        <?php if ($user_type == USER_TYPE_ADMIN) { ?>
                                            <h6 class="text-danger"><i class="fa fa-user"></i> ตั้งกระทู้โดย : ผู้ดูแลระบบ</h6>
                                        <?php }
                                        if ($user_type == USER_TYPE_TEACHER) { ?>
                                            <h6 class="text-danger"><i class="fa fa-user"></i> ตั้งกระทู้โดย : คุณครู/อาจารย์</h6>
                                        <?php } ?>

                                        <!--แสดงชื่อผู้ใช้ โดยให้ดูรายละเอียดของผู้ใชอื่นได้ ยกเว้น แอดมิน และ ครู -->
                                        <?php if ($user_type != USER_TYPE_TEACHER && $user_type != USER_TYPE_ADMIN) { ?>
                                            <label><a class="nav-link" href="profile.php?id=<?php echo $user_id ?>"><?php echo $fullname; ?></a></label>
                                        <?php } else { ?>
                                            <label><?php echo $fullname; ?></label>
                                        <?php } ?>

                                        <span>โพสต์เมื่อ : <?php echo thaiDateFormat($created_at) . ' ' . $time_only ?></span>
                                    </div>
                                </div>
                            </div>

                            <h3><?php echo $title; ?></h3>
                            <p><?php echo $content ?></p>

                            <!-- like topic forum -->
                            <div class="d-flex flex-wrap align-items-center gap-3 mt-4">

                                <button onclick="likeTopic(<?php echo $id ?>)" id="likeButton" class="btn <?php echo $check_like_post ? 'btn-primary' : 'btn-outline-primary' ?>">
                                    <i class="fas fa-thumbs-up"></i> ถูกใจ
                                </button>


                                <div>
                                    <span id="likeCount"><?php echo $like_count ?></span> คนถูกใจ
                                </div>



                            </div>

                        </div>

                    </div>
                    <div class="card">
                        <div class="header">
                            <h2>ความคิดเห็น <?php echo count($comment_list) ?> รายการ</h2>
                        </div>
                        <div class="body">
                            <!-- แสดงความคิดเห็น -->
                            <form id="createComment">
                                <div class="mb-3 d-flex flex-column gap-3">
                                    <div>
                                        <input type="hidden" value="<?php echo $id ?>" name="post_id">
                                        <label for="form-control">เริ่มพูดคุยในกระดานสนทนา</label>
                                        <textarea class="form-control" name="comment" rows="5" placeholder="เริ่มการสนทนาของคุณที่นี่..." id="commentContent"></textarea>
                                        <div id="errorInput" class="invalid-feedback">กรุณากรอกข้อความ</div>
                                    </div>
                                    <div>
                                        <button type="button" id="submitComment" class="btn btn-success">แสดงความคิดเห็น</button>
                                    </div>
                                </div>
                            </form>

                            <hr>

                            <!-- รายการความคิดเห็น -->
                            <ul class="comment-reply list-unstyled">
                                <?php if (!empty($comment_list)) { ?>
                                    <?php
                                    $num = 0;
                                    foreach ($comment_list as $item) {
                                        $num += 1;
                                        $comment_id = $item['comment_id'];
                                        $user_comment = $item['first_name'] . ' ' . $item['last_name'];
                                        $created_at = thaiDateFormat($item['created_at']) . ' ' . date("H:i", strtotime($item['created_at']));
                                        $content_comment = $item['content'];
                                        $user_id = $item['user_id'];


                                        $image = $item['image'];
                                        $user_type = $item['user_type'];
                                        $user_path = $user_type == USER_TYPE_ALUMNI ? 'alumni' : ($user_type == USER_TYPE_STUDENT ? 'student' : ($user_type == USER_TYPE_ADMIN ? 'admin' : 'teacher'));

                                        $path = '';

                                        //เช็คว่าใช่ comment ของ user ที่ login อยู่หรือไม่
                                        $is_comment_user = ($user_id == $_SESSION['user']['id'] && $user_type == $_SESSION['user']['user_type']);

                                        if (empty($image)) {
                                            $path = '../../assets/images/user/no-image-profile.jpg';
                                        } else {
                                            $path = '../../assets/images/user/' . $user_path . '/' . $image;
                                        }
                                    ?>

                                        <li class="row position-relative">
                                            <div class="dropdown position text-end">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="background: transparent; border: none; box-shadow: none;">
                                                    <i class="fas fa-ellipsis-v" style="font-size: 1rem; color: black;"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#reportModal" onclick="openModalReport(<?php echo $comment_id ?>)">รายงานปัญหาความคิดเห็น</a></li>
                                                    <?php if ($is_comment_user || $_SESSION['user']['user_type'] == USER_TYPE_ADMIN) { ?>
                                                        <li><a class="dropdown-item text-danger" id="DeleteComment" onclick="deleteComment(<?php echo $comment_id ?>)">ลบความคิดเห็น</a></li>
                                                    <?php } ?>
                                                </ul>
                                            </div>

                                            <div class="icon-box col-auto d-flex align-items-center"><img class="rounded-circle img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;" src="<?php echo $path ?>"></div>
                                            <div class="text-box col-8 p-l-0 p-r0">
                                                <?php if ($user_type == USER_TYPE_ADMIN) { ?>
                                                    <h5 class="text-danger"><i class="fa fa-user"></i> ตอบกลับโดยผู้ดูแลระบบ</h5>
                                                <?php }
                                                if ($user_type == USER_TYPE_TEACHER) { ?>
                                                    <h5 class="text-danger"><i class="fa fa-user"></i> ตอบกลับโดยคุณครู / อาจารย์</h5>
                                                <?php } ?>

                                                <!--แสดงชื่อผู้ใช้ โดยให้ดูรายละเอียดของผู้ใชอื่นได้ ยกเว้น แอดมิน และ ครู -->
                                                <?php if ($user_type != USER_TYPE_TEACHER && $user_type != USER_TYPE_ADMIN) { ?>
                                                    <p><a class="nav-link" href="profile.php?id=<?php echo $user_id ?>"><?php echo $user_comment; ?></a></p>
                                                <?php } else { ?>
                                                    <p><?php echo $user_comment; ?></p>
                                                <?php } ?>

                                                <h5 class="m-b-0"><?php echo $content_comment ?></h5>
                                                <ul class="list-inline">
                                                    <li><a class="nav-link" href="javascript:void(0);"><?php echo $created_at ?></a></li>
                                                    <li><a href="javascript:void(0);" onclick="replyComment('reply-form-<?php echo $num; ?>')">ตอบกลับ</a></li>
                                                </ul>
                                            </div>

                                            <!-- Reply Form (Hidden by default) -->
                                            <div id="reply-form-<?php echo $num; ?>" class="reply-section" style="display: none;">
                                                <div class="reply-form">
                                                    <form action="post" id="reply-comment-<?php echo $num ?>">
                                                        <div class="d-flex flex-column ">
                                                            <input type="hidden" name="post_id" value="<?php echo $id ?>">
                                                            <input type="hidden" name="parent_comment_id" value="<?php echo $comment_id ?>">
                                                            <textarea class="form-control mb-3" id="inputReplyComment" name="comment" rows="3" placeholder="เขียนการตอบกลับ..."></textarea>
                                                            <div id="errorInputReplyComment" class="invalid-feedback">กรุณากรอกข้อความ</div>
                                                            <div class="d-flex gap-2">
                                                                <button type="button" onclick="submitReplyComment('reply-comment-<?php echo $num ?>')" class="btn btn-primary btn-sm">ตอบกลับ</button>
                                                                <button type="button" onclick="closeReplyComment('reply-form-<?php echo $num; ?>')" class="btn btn-outline-secondary btn-sm">ยกเลิก</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- การตอบกลับความคิดเห็น -->
                                            <?php $reply_comment_list = $comment->getReplyComment($id, $comment_id);
                                            if (!empty($reply_comment_list)) { ?>
                                                <?php
                                                $num_reply = 0;
                                                foreach ($reply_comment_list as $reply) {
                                                    $num_reply += 1;
                                                    $user_reply = $reply['first_name'] . ' ' . $reply['last_name'];
                                                    $reply_comment = $reply['content'];
                                                    $created_at_reply = thaiDateFormat($reply['created_at']) . ' ' . date("H:i", strtotime($reply['created_at']));

                                                    $image_reply = $reply['image'];
                                                    $user_type_reply = $reply['user_type'];
                                                    $user_path_reply = $user_type_reply == USER_TYPE_ALUMNI ? 'alumni' : ($user_type_reply == USER_TYPE_STUDENT ? 'student' : ($user_type_reply == USER_TYPE_ADMIN ? 'admin' : 'teacher'));

                                                    $path_reply = '';

                                                    $is_comment_reply_user = ($reply['user_id'] == $_SESSION['user']['id'] && $reply['user_type'] == $_SESSION['user']['user_type']);

                                                    if (empty($image)) {
                                                        $path_reply = '../../assets/images/user/no-image-profile.jpg';
                                                    } else {
                                                        $path_reply = '../../assets/images/user/' . $user_path_reply . '/' . $image_reply;
                                                    }

                                                ?>

                                                    <div class="p-3 border rounded mt-3">
                                                        <h7>การตอบกลับความคิดเห็นที่ <?php echo $num_reply ?></h7>

                                                        <div class="dropdown position-absolute end-0">
                                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="background: transparent; border: none; box-shadow: none;">
                                                                <i class="fas fa-ellipsis-v" style="font-size: 1rem; color: black;"></i>
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#reportModal">รายงานปัญหาความคิดเห็น</a></li>
                                                                <?php if ($is_comment_reply_user || $_SESSION['user']['user_type'] == USER_TYPE_ADMIN) { ?>
                                                                    <li><a class="dropdown-item text-danger" id="DeleteComment" onclick="deleteComment(<?php echo $reply['comment_id'] ?>)">ลบการตอบกลับความคิดเห็น</a></li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>

                                                        <div class="row mt-2">
                                                            <div class="icon-box col-auto d-flex align-items-center"><img class="rounded-circle img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;" src="<?php echo $path_reply ?>"></div>
                                                            <div class="text-box col-8 p-l-0 p-r0">
                                                                <?php if ($user_type_reply == USER_TYPE_ADMIN) { ?>
                                                                    <h6 class="text-danger"><i class="fa fa-user"></i> ตอบกลับโดยผู้ดูแลระบบ</h6>
                                                                <?php }
                                                                if ($user_type_reply == USER_TYPE_TEACHER) { ?>
                                                                    <h6 class="text-danger"><i class="fa fa-user"></i> ตอบกลับโดยคุณครู / อาจารย์</h6>
                                                                <?php } ?>

                                                                <!--แสดงชื่อผู้ใช้ โดยให้ดูรายละเอียดของผู้ใชอื่นได้ ยกเว้น แอดมิน และ ครู -->
                                                                <?php if ($user_type != USER_TYPE_TEACHER && $user_type != USER_TYPE_ADMIN) { ?>
                                                                    <p><a class="nav-link" href="profile.php?id=<?php echo $user_id ?>"><?php echo $user_reply; ?></a></p>
                                                                <?php } else { ?>
                                                                    <p><?php echo $user_reply; ?></p>
                                                                <?php } ?>

                                                                <h6 class="m-b-0"><?php echo $reply_comment ?></h6>
                                                                <ul class="list-inline">
                                                                    <li><a class="nav-link" href="javascript:void(0);"><?php echo $created_at_reply ?></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php }
                                            } ?>


                                        </li>
                                        <hr>
                                    <?php }
                                } else { ?>
                                    <div class="text-center text-danger py-4">ขณะนี้ยังไม่มีการพูดคุยในกระทู้นี้</div>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- Modal Report Post -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
        <form action="" id="formReport">
            <input type="hidden" name="post_id" value="<?php echo $id ?>" id="postId">
            <input type="hidden" name="comment_id" id="commentId">

            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="modalTitle">รายงานปัญหา</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <textarea name="reason" id="reportReason" required class="form-control" placeholder="เขียนปัญหาที่คุณพบเกี่ยวกับกระทู้ หรือ ความคิดเห็น นี้ เช่น การใช้ถ้อยคำที่ไม่เหมาะสม ..."></textarea>
                        <div id="errorInputReport" class="invalid-feedback">กรุณากรอกข้อความ</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="button" id="submitReport" class="btn btn-primary">ยืนยันการรายงานปัญหา</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php include '../../includes/footer.php' ?>

    <script src="../../assets/js/bootstrap.min.js"></script>
    <script src="../../assets/alerts/modal.js"></script>
    <script src="../function/validate_form.js"></script>
    <script src="../../assets/js/sweetalert2.all.min.js"></script>
</body>
<script>
    const btnSubmitComment = document.getElementById('submitComment');
    const btnSubmitReport = document.getElementById('submitReport');
    const btnSubmitReplyComment = document.getElementById('submitReplyComment');

    //ตอบกลับความคิดเห็น
    function submitReplyComment(formId) {
        const formElement = document.getElementById(formId);
        const replyCommentInput = formElement.querySelector('#inputReplyComment');
        const errorInput = formElement.querySelector('#errorInputReplyComment');

        const formData = new FormData(formElement);
        const formUrl = new URLSearchParams(formData);

        if (!replyCommentInput.value.trim()) {
            replyCommentInput.classList.add('is-invalid');
            errorInput.textContent = 'กรุณากรอกข้อมูล';
            return;
        }else if(hasBadWords(replyCommentInput.value)){
            replyCommentInput.classList.add('is-invalid');
            errorInput.textContent = 'เนื้อหามีคำไม่เหมาะสม กรุณาแก้ไข'
            return;
        } 
        else {
            replyCommentInput.classList.remove('is-invalid');
            errorInput.textContent = '';
        }

        fetch('create_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formUrl
            })
            .then(response => response.json())
            .then(response => {
                if (response.result === true) {
                    modalAlert('ตอบกลับความคิดเห็นสำเร็จ', 'ตอบกลับความคิดเห็นสำเร็จ', 'success')
                        .then(() => location.reload());
                } else {
                    modalAlert('ตอบกลับความคิดเห็นไม่สำเร็จ เกิดข้อผิดพลาดขึ้น', response.message, 'error');
                }
            })
            .catch(error => {
                modalAlert('การเชื่อมต่อล้มเหลว', 'ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้', 'error');
                console.error('Fetch error:', error);
            });
    }

    //ส่งรายงานปัญหา
    btnSubmitReport.addEventListener('click', () => {
        const reportInput = document.getElementById('reportReason');
        const errorInput = document.getElementById('errorInputReport');

        if (!reportInput.value.trim()) {
            reportInput.classList.add('is-invalid');
            errorInput.textContent = 'กรุณากรอกข้อมูล';
            return;
        }

        const form = document.getElementById('formReport');
        const formData = new FormData(form);
        const formUrl = new URLSearchParams(formData);

        modalConfirm('ยืนยันการรายงานปัญหา', 'คุณต้องการรายงานปัญหาเกี่ยวกับกระทู้นี้ใช่ไหม?')
            .then(result => {
                if (result.isConfirmed) {
                    fetch('send_report.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: formUrl
                        })
                        .then(response => response.json())
                        .then(response => {
                            if (response.result) {
                                modalAlert('รายงานปัญหาสำเร็จ', 'รายงานปัญหาสำเร็จ', 'success')
                                    .then(() => location.reload());
                            } else {
                                modalAlert('รายงานปัญหาไม่สำเร็จ เกิดข้อผิดพลาดขึ้น', response.message, 'error');
                            }
                        })
                        .catch(error => {
                            modalAlert('การเชื่อมต่อล้มเหลว', 'ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้', 'error');
                            console.error('Fetch error:', error);
                        });
                }

            })
    })

    //แสดงความคิดเห็น
    btnSubmitComment.addEventListener('click', () => {
        const commentInput = document.getElementById('commentContent');
        const errorInput = document.getElementById('errorInput');

        const form = document.getElementById('createComment');
        const formData = new FormData(form);

        if (!commentInput.value.trim()) {
            commentInput.classList.add('is-invalid');
            errorInput.textContent = 'กรุณากรอกข้อมูล';
            return;
        }

        if (hasBadWords(commentInput.value)) {
            commentInput.classList.add('is-invalid');
            errorInput.textContent = 'เนื้อหามีคำไม่เหมาะสม กรุณาแก้ไข'
            return;
        }

        fetch('create_comment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(response => {
                if (response.result === true) {
                    modalAlert('แสดงความคิดเห็นสำเร็จ', 'แสดงความคิดเห็นสำเร็จ', 'success')
                        .then(() => location.reload());
                } else {
                    modalAlert('แสดงความคิดเห็นไม่สำเร็จ เกิดข้อผิดพลาดขึ้น', response.message, 'error');
                }
            })
            .catch(error => {
                modalAlert('การเชื่อมต่อล้มเหลว', 'ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้', 'error');
                console.error('Fetch error:', error);
            });
    });

    function deleteComment(commentId) {
        modalConfirm('ยืนยันการลบความคิดเห็น', 'คุณต้องการลบความคิดเห็นของท่านช่ไหม?')
            .then(result => {
                if (result.isConfirmed) {
                    fetch('delete_comment.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'comment_id=' + commentId
                        })
                        .then(response => response.json())
                        .then(response => {
                            if (response.result) {
                                modalAlert('ลบความคิดเห็นสำเร็จ', 'ลบความคิดเห็นสำเร็จ', 'success')
                                    .then(() => location.reload());
                            } else {
                                modalAlert('ลบความคิดเห็นไม่สำเร็จ เกิดข้อผิดพลาดขึ้น', response.message, 'error');
                            }
                        })
                        .catch(error => {
                            modalAlert('การเชื่อมต่อล้มเหลว', 'ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้', 'error');
                            console.error('Fetch error:', error);
                        });
                }

            })
    }

    function deleteTopic(id, stringImage) {
        modalConfirm('ยืนยันการลบกระทู้', 'คุณต้องการลบกระทู้ของท่านใช่ไหม?')
            .then(result => {
                if (result.isConfirmed) {
                    fetch('delete_topic.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'id=' + id + '&image=' + stringImage
                        })
                        .then(response => response.json())
                        .then(response => {
                            if (response.result) {
                                modalAlert('ลบกระทู้สำเร็จ', 'ลบกระทู้สำเร็จ', 'success')
                                    .then(() => window.location.href='index.php');
                            } else {
                                modalAlert('ลบกระทู้ไม่สำเร็จ เกิดข้อผิดพลาดขึ้น', response.message, 'error');
                            }
                        })
                        .catch(error => {
                            modalAlert('การเชื่อมต่อล้มเหลว', 'ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้', 'error');
                            console.error('Fetch error:', error);
                        });
                }

            })
    }


    //ถูกใจกระทู้ & ยกเลิกการถูกใจ
    function likeTopic(post_id) {
        const btnLike = document.getElementById('likeButton');
        const numberLike = document.getElementById('likeCount');

        let action = '';

        if (btnLike.classList.contains('btn-outline-primary')) {
            btnLike.classList.remove('btn-outline-primary');
            btnLike.classList.add('btn-primary');
            numberLike.textContent = Number(numberLike.textContent) + 1;
            action = 'like';
        } else {
            btnLike.classList.remove('btn-primary');
            btnLike.classList.add('btn-outline-primary');
            numberLike.textContent = Number(numberLike.textContent) - 1;
            action = 'unlike';
        }

        fetch('like_post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'post_id=' + encodeURIComponent(post_id) + '&action=' + action
            })
            .then(response => response.json())
            .then(response => {
                console.log('กำลังประมวลผล');
                if (!response.result) {
                    modalAlert('ไม่สามารถถูกใจกระทู้นี้ได้', 'เกิดข้อผิดพลาดขึ้นกับคำขอ', 'error');
                }
            })
            .catch(error => {
                modalAlert('การเชื่อมต่อล้มเหลว', 'ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้', 'error');
                console.error('Fetch error:', error);
            })
            .finally(() => {
                console.log('กระทำสำเร็จ');
            });
    }

    function openModalReport(commentId) {
        document.getElementById('commentId').value = commentId;;
    }

    function closeModalReport(commentId) {
        document.getElementById('commentId').value = '';;
    }

    function replyComment(idTag) {
        const elementReplyForm = document.getElementById(idTag);
        elementReplyForm.style.display = 'block';
    }

    function closeReplyComment(idTag) {
        const elementReplyForm = document.getElementById(idTag);
        elementReplyForm.style.display = 'none';
    }
</script>

</html>