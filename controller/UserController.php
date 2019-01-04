<?php

require_once('model/UserModel.php');

/**
 * Siehe Dokumentation im DefaultController.
 */
class UserController
{
    public function index()
    {

        $view = new View('user_index');
        $view->title = 'Benutzer';
        $view->heading = 'Benutzer';
        $view->display();
    }

    public function login()
    {
        $view = new View('default_index');
        $view->display();
    }

    public function check_login() {
        $message = array();
        if (isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["password"])) {
            $username = htmlspecialchars($_POST["username"]);
            $password = htmlspecialchars($_POST["password"]);
            $model = new UserModel();
            $result = $model->log_in($username, $password);
            if ($result->num_rows == 1) {
                $row = $result->fetch_object();
                $_SESSION ['user'] ['name'] = $row->benutzername;
                $_SESSION ['user'] ['id'] = $row->id;
                $_SESSION ['loggedin'] = true;
                header ('Location: /user/index');
            } else {
                $message[] = 'Login Fehlgeschlagen!';
                $this->message = $message;
                $this->login();
            }
        }else {
            $this->login();
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
}
