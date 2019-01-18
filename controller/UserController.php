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
            switch ($_SESSION['userType']['id']) {
                case 1:
                    header("Location: /admin/index");
                    break;
                case 2:
                    header("Location: /prof/index");
                    break;
                case 3:
                    $this->index();
                    break;
            }
        } else {
            $this->login();
        }
    }

    public function login()
    {
        $view = new View('user_login');
        $view->display();
    }

    public function lesions()
    {
        $view = new View('user_lesions');
        $view->display();
    }

    public function check_login()
    {
        $message = array();
        if (isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["password"])) {
            $username = htmlspecialchars($_POST["username"]);
            $password = htmlspecialchars($_POST["password"]);
            $model = new UserModel();
            $result = $model->log_in($username);
            if ($result->num_rows == 1) {
                $row = $result->fetch_object();
                if ($row->initial_pw == 0) {
                    $verifyPassword = password_verify($password, $row->password);
                    if ($verifyPassword) {
                        $_SESSION ['user'] ['name'] = $row->email;
                        $_SESSION ['user'] ['id'] = $row->id;
                        $_SESSION ['loggedin'] = true;
                        $_SESSION ['userType'] ['id'] = $row->user_type;
                        $result = $model->getUsertypeById($row->user_type);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_object();
                            $_SESSION ['userType'] ['name'] = $row->bezeichnung;
                        } else {
                            $_SESSION ['userType'] ['name'] = "Unbekannt";
                        }

                        switch ($row->id) {
                            case 1:
                                header("Location: /admin/index");
                                break;
                            case 2:
                                header("Location: /prof/index");
                                break;
                            case 3:
                                $this->index();
                                break;
                        }
                    } else {
                        $message[] = 'Falsches Passwort!';
                        $this->message = $message;
                        $this->login();
                    }
                } else {
                    if ($password == $row->password) {
                        $_SESSION ['user'] ['name'] = $row->email;
                        $_SESSION ['user'] ['id'] = $row->id;
                        $_SESSION ['loggedin'] = true;
                        $_SESSION ['userType'] ['id'] = $row->user_type;
                        $result = $model->getUsertypeById($row->user_type);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_object();
                            $_SESSION ['userType'] ['name'] = $row->bezeichnung;
                        } else {
                            $_SESSION ['userType'] ['name'] = "Unbekannt";
                        }

                        $message[] = 'Bitte Passwort ändern!';
                        $this->message = $message;
                        $this->new_password();
                    }
                }

            } else {
                $message[] = 'Login Fehlgeschlagen!';
                $this->message = $message;
                $this->login();
            }
        } else {
            $this->login();
        }
    }

    public function new_password()
    {
        $view = new View('user_password');

        $view->display();
    }

    public function logout()
    {
        session_destroy();
        header('Location: /');
    }

    public function edit_profile()
    {
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
            $view = new View('user_profile');
            $view->display();
        } else {
            $message[] = "Bitte zuerst einloggen!";
            $this->message = $message;
            $this->login();
        }
    }

    public function check_changePassword()
    {
        if (isset($_POST["old_password"]) && !empty($_POST["old_password"]) && isset($_POST["new_password"]) && !empty($_POST["new_password"])) {
            $old_password = htmlspecialchars($_POST["old_password"]);
            $new_password = htmlspecialchars($_POST["new_password"]);
            $password_pattern = "#(?=^.{8,}$)^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$#";
            if (preg_match($password_pattern, $new_password)) {
                $model = new UserModel();


                $result = $model->readById($_SESSION['user']['id']);

                if ($result->initial_pw == 0) {
                    $affectedRows = $model->change_password($new_password, $_SESSION['user']['id']);
                } else {
                    $affectedRows = $model->update_password($new_password, $old_password, $_SESSION['user']['id']);
                }

                if ($affectedRows == 1) {
                    $message[] = 'Passwort geändert!';
                    $this->message = $message;
                    $this->index();
                } else {
                    $message[] = 'Passwort ändern fehlgeschlagen!';
                    $this->message = $message;
                    $this->edit_profile();
                }
            } else {
                $message[] = 'Passwort entspricht nicht den Vorgaben!';
                $this->message = $message;
                $this->edit_profile();
            }
        } else {
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
