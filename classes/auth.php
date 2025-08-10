<?php 

class Auth {

    public function isLoggedInAdmin(){
        return isset($_SESSION['user']) && ($_SESSION['user']['user_type'] === USER_TYPE_ADMIN || $_SESSION['user']['user_type'] === USER_TYPE_TEACHER);
    }

    public function isLoggedInUser(){
        return isset($_SESSION['user']) && ($_SESSION['user']['user_type'] === USER_TYPE_ALUMNI || $_SESSION['user']['user_type'] === USER_TYPE_STUDENT);
    }

    public function isLoggedInAll(){
        return isset($_SESSION['user']) && ($_SESSION['user']['user_type'] === USER_TYPE_ALUMNI || $_SESSION['user']['user_type'] === USER_TYPE_STUDENT || $_SESSION['user']['user_type'] === USER_TYPE_ADMIN || $_SESSION['user']['user_type'] === USER_TYPE_TEACHER );
    }
}

?>