<?php


namespace App\Authentication;


class Authentication
{
    public static function restrict_authenticated($usertype)
    {
        if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
            http_response_code(401);
            header('Location: /user/login');
            exit();
        }
        switch ($usertype) {
            case UserTypes::$_ADMIN:
                if ($_SESSION['userType']['id'] != UserTypes::$_ADMIN) {
                    http_response_code(403);
                    header('Location: /');
                    exit();
                }
                break;
            case UserTypes::$_PROF:
                if ($_SESSION['userType']['id'] != UserTypes::$_PROF) {
                    http_response_code(403);
                    header('Location: /');
                    exit();
                }
                break;
            case UserTypes::$_STUD:
                if ($_SESSION['userType']['id'] != UserTypes::$_STUD) {
                    http_response_code(403);
                    header('Location: /');
                    exit();
                }
                break;
            case UserTypes::$_PROF_STUD:
                if (!($_SESSION['userType']['id'] == UserTypes::$_PROF || $_SESSION['userType']['id'] == UserTypes::$_STUD)) {
                    http_response_code(403);
                    header('Location: /');
                    exit();
                }
                break;
            case UserTypes::$_ADMIN_PROF:
                if (!($_SESSION['userType']['id'] == UserTypes::$_ADMIN || $_SESSION['userType']['id'] == UserTypes::$_PROF)) {
                    http_response_code(403);
                    header('Location: /');
                    exit();
                }
                break;
        }
    }
}