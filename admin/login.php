<?php
include '../config/config.php';
require_once '../classes/admin.php';

$db = new Database();
$conn = $db->connect();
$admin = new Admin($conn, 'admin');

//ตรวจสมอบการเข้าสู่ระบบ
if ($admin->isLoggedIn()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {

    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $result = $admin->login($username, $password);

    echo json_encode($result);

    exit;
}

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

</head>
<style>
    .card-container.card {
        max-width: 500px;
        padding: 40px 40px;
    }

    .btn {
        font-weight: 700;
        height: 36px;
        -moz-user-select: none;
        -webkit-user-select: none;
        user-select: none;
        cursor: default;
    }

    /*
 * Card component
 */
    .card {
        background-color: #F7F7F7;
        /* just in case there no content*/
        padding: 20px 25px 30px;
        margin: 0 auto 25px;
        margin-top: 50px;
        /* shadows and rounded borders */
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    }

    .profile-img-card {
        width: 96px;
        height: 96px;
        margin: 0 auto 10px;
        display: block;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        border-radius: 50%;
    }

    /*
 * Form styles
 */
    .profile-name-card {
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        margin: 10px 0 0;
        min-height: 1em;
    }

    .reauth-email {
        display: block;
        color: #404040;
        line-height: 2;
        margin-bottom: 10px;
        font-size: 14px;
        text-align: center;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    .form-signin #inputEmail,
    .form-signin #inputPassword {
        direction: ltr;
        height: 44px;
        font-size: 16px;
    }

    .form-signin input[type=email],
    .form-signin input[type=password],
    .form-signin input[type=text],
    .form-signin button {
        width: 100%;
        display: block;
        margin-bottom: 10px;
        z-index: 1;
        position: relative;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    .form-signin .form-control:focus {
        border-color: rgb(104, 145, 162);
        outline: 0;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgb(104, 145, 162);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgb(104, 145, 162);
    }

    .btn.btn-signin {
        /*background-color: #4d90fe; */
        background-color: rgb(104, 145, 162);
        /* background-color: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));*/
        padding: 0px;
        font-weight: 700;
        font-size: 14px;
        height: 36px;
        -moz-border-radius: 3px;
        -webkit-border-radius: 3px;
        border-radius: 3px;
        border: none;
        -o-transition: all 0.218s;
        -moz-transition: all 0.218s;
        -webkit-transition: all 0.218s;
        transition: all 0.218s;
    }

    .btn.btn-signin:hover,
    .btn.btn-signin:active,
    .btn.btn-signin:focus {
        background-color: rgb(12, 97, 33);
    }

    .forgot-password {
        color: rgb(104, 145, 162);
    }

    .forgot-password:hover,
    .forgot-password:active,
    .forgot-password:focus {
        color: rgb(12, 97, 33);
    }
</style>

<body>

    <?php include '../includes/navbar.php' ?>

    <div class="container d-flex align-items-center" style="height: 70vh;">
        <div class="card card-container">
            <h3 class="text-center mb-3">เข้าสู่ระบบสำหรับผู้ดูแลระบบ</h3>
            <form id="formLogin" method="post" class="form-signin">
                <span id="reauth-email" class="reauth-email"></span>
                <input type="text" name="username" id="inputEmail" class="form-control" placeholder="ชื่อผู้ใช้" required autofocus>
                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="รหัสผ่าน" required>
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="button" onclick="login()" >เข้าสู่ระบบ</button>
            </form><!-- /form -->
            <a href="#" class="forgot-password">
                ลืมรหัสผ่าน
            </a>
        </div><!-- /card-container -->
    </div><!-- /container -->



    <?php include '../includes/footer.php' ?>

    <script src="../assets/js/bootstrap.bundle.js"></script>
    <script src="../assets/js/sweetalert2.all.min.js"></script>
    <script src="../assets/alerts/modal.js"></script>
    <script>
        function login() {
            const form = document.getElementById('formLogin');
            const formData = new FormData(form);
            const formParams = new URLSearchParams(formData);

            fetch('login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: formParams
                })
                .then(response => response.json())
                .then(response =>{
                    if(response.result){
                        window.location.href = 'index.php';
                    }else{
                        modalAlert('เข้าสู่ระบบไม่สำเร็จ', response.message, 'error');
                    }
                })
                .catch(error => {
                    modalAlert(`การเชื่อมต่อล้มเหลว`, "ไม่สามารถติดต่อกับเซิร์ฟเวอร์ได้", "error")
                    console.error('Fetch error:', error);
                });
        }
    </script>

</body>

</html>