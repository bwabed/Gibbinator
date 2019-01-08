<?php

require_once('model/UserModel.php');

/**
 * Siehe Dokumentation im DefaultController.
 */
class UserController
{

    private $message;

    public function __construct()
    {
        $view = new View('header', array('title' => 'Benutzer', 'heading' => 'Benutzer'));
        $view->display();
    }

    public function index()
    {
        if (!empty($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            $view = new View('user_index');
            $view->display();
        }
        else {
            $this->login();
        }
    }

    public function login()
    {
        $view = new View('default_index');
        $view->display();
    }

    public function lessions()
    {

    }

    public function check_login() {
        $message = array();
        if (isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["password"])) {
            $username = htmlspecialchars($_POST["username"]);
            $password = htmlspecialchars($_POST["password"]);
            $model = new UserModel();
            $result = $model->log_in($username);
            if ($result->num_rows == 1) {
                $row = $result->fetch_object();
                $verifyPassword = password_verify($password, $row->password);
                if ($verifyPassword) {


                    $_SESSION ['user'] ['name'] = $row->email;
                    $_SESSION ['user'] ['id'] = $row->ID;
                    $_SESSION ['loggedin'] = true;
                    $_SESSION ['userType'] = $row->user_type;
                    $this->index();

                } else {
                    $message[] = 'Falsches Passwort!';
                    $this->message = $message;
                    $this->login();
                }
            } else {
                $message[] = 'Login Fehlgeschlagen!';
                $this->message = $message;
                $this->login();
            }
        }else {
            $this->login();
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /');
        $view = new View('default_index');
        $view->display();
    }

    public function edit_profile() {
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
            $view = new View('user_profile');
            $view->display();
        }
        else {
            $message[] = "Bitte zuerst einloggen!";
            $this->message = $message;
            $this->login();
        }
    }

    public function check_changePassword() {
        if (isset($_POST["old_password"]) && !empty($_POST["old_password"]) && isset($_POST["new_password"]) && !empty($_POST["new_password"])) {
            $old_password = htmlspecialchars($_POST["old_password"]);
            $new_password = htmlspecialchars($_POST["new_password"]);
            $password_pattern = "#(?=^.{8,}$)^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$#";
            if (preg_match($password_pattern, $new_password)) {
                $model = new UserModel();
                $affectedRows = $model->change_password($old_password,$new_password,$_SESSION['user']['id']);
                if ($affectedRows == 1) {
                    $message[] = 'Passwort geändert!';
                    $this->message = $message;
                    header("Location: /user/index");
                }
                else {
                    $message[] = 'Passwort ändern fehlgeschlagen!';
                    $this->message = $message;
                    $this->edit_profile();
                }
            }
            else {
                $message[] = 'Passwort ändern fehlgeschlage!';
                $this->message = $message;
                $this->edit_profile();
            }
        }
        else {
            $this->edit_profile();
        }
    }

    public function create()
    {
        $view = new View('user_create');
        $view->title = 'Benutzer erstellen';
        $view->heading = 'Benutzer erstellen';
        $view->display();
    }

    public function doCreate()
    {
        if ($_POST['send']) {
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userRepository = new UserModel();
            $userRepository->create($firstName, $lastName, $email, $password);
        }

        // Anfrage an die URI /user weiterleiten (HTTP 302)
        header('Location: /user');
    }

    public function delete()
    {
        $userRepository = new UserModel();
        $userRepository->deleteById($_GET['id']);

        // Anfrage an die URI /user weiterleiten (HTTP 302)
        header('Location: /user');
    }

    public function __destruct()
    {
        $view = new View('footer');
        $view->message = $this->message;
        $view->display();
    }
}
