<?php 

class Auth {

    public function isLoggedInAdmin(){
        return isset($_SESSION['user']) && ($_SESSION['user']['role'] === USER_TYPE_ADMIN || $_SESSION['user']['role'] === USER_TYPE_TEACHER);
    }

    public function isLoggedInUser(){
        return isset($_SESSION['user']) && ($_SESSION['user']['role'] === USER_TYPE_ALUMNI || $_SESSION['user']['role'] === USER_TYPE_STUDENT);
    }
}

?>