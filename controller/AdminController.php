<?php

require_once('model/UserModel.php');
require_once('model/UsertypeModel.php');
/**
 * Created by PhpStorm.
 * User: dimit
 * Date: 12.01.2019
 * Time: 11:57
 */

class AdminController
{
    private $message;

    public function __construct()
    {
        $view = new View('admin_header', array('title' => 'Startseite', 'heading' => 'Startseite'));
        $view->display();
    }

    public function index()
    {

        $userModel = new UserModel();
        try {
            $result = $userModel->readAll();
        } catch (Exception $e) {
            $result = null;
        }

        $view = new View('admin_index');
        $view->users = $result;
        $view->display();

    }

    public function infra() {

    }

    public function edit_user() {
        $view = new View('admin_edit');

        $userModel = new UserModel();

        $view->userData = $userModel->readById($_POST['user_id']);
        $view->display();
    }

    public function delete_selected() {
        if (!empty($_POST['users'])) {
            $userModel = new UserModel();

            foreach ($_POST['users'] as $users) {
                $userModel->deleteById($users);
            }

            $this->index();
        }
    }

    public function new_user() {
        $view = new View('admin_new');

        $usertypeModel = new UsertypeModel();

        $view->usertypes = $usertypeModel->readAll();
        $view->display();
    }

    public function create_user() {
        if (!empty($_POST['new_username']) && !empty($_POST['new_password']) && !empty($_POST['new_vorname']) && !empty($_POST['new_nachname']) && !empty($_POST['usertype_select'])) {
            $userModel = new UserModel();
            $checkEmail = $userModel->check_if_email_exists($_POST['new_username']);
            if ($checkEmail->num_rows == 0) {
                $userModel->create($_POST['new_vorname'], $_POST['new_nachname'], $_POST['new_username'], $_POST['new_password'], $_POST['usertype_select']);
                $message[] = 'Benutzer wurde erstellt.';
                $this->message = $message;
                $this->index();
            } else {
                $message[] = 'Email ist schon vergeben!';
                $this->message = $message;
                $this->new_user();
            }
        } else {
            $message[] = 'Bitte alle Angaben ausfüllen!';
            $this->message = $message;
            $this->new_user();
        }
    }

    public function __destruct()
    {
        $view = new View('footer');
        $view->message = $this->message;
        $view->display();
    }
}