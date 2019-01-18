<?php

require_once('model/UserModel.php');
require_once('model/UsertypeModel.php');
require_once('model/GebaeudeModel.php');
require_once('model/KlassenModel.php');
require_once('model/AbteilungsModel.php');

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

    public function add_building()
    {
        if (!empty($_POST['build_name']) && !empty($_POST['build_street']) && !empty($_POST['build_number']) && !empty($_POST['build_plz']) && !empty($_POST['build_ort'])) {
            $buildModel = new GebaeudeModel();
            try {
                $buildModel->addBuilding($_POST['build_name'], $_POST['build_street'], $_POST['build_number'], $_POST['build_plz'], $_POST ['build_ort']);
                $message[] = 'Erfolgreich erstellt!';
                $this->message = $message;
                $this->infra();
            } catch (Exception $e) {
                $message[] = 'Konnte nicht eingefügt werden!';
                $this->message = $message;
                $this->infra();
            }
        } else {
            $message[] = 'Bitte alle Felder ausfüllen!';
            $this->message = $message;
            $this->infra();
        }
    }

    public function infra()
    {

        $gebaeudeModel = new GebaeudeModel();
        try {
            $result = $gebaeudeModel->readAll();
        } catch (Exception $e) {
            $result = null;
        }

        $view = new View('admin_infra');
        $view->gebaeude = $result;
        $view->display();
    }

    public function add_room()
    {
        if (!empty($_POST['room_name']) && !empty($_POST['room_gebaeude_select'])) {

        } else {
            $message[] = 'Bitte alle Felder ausfüllen!';
            $this->message = $message;
            $this->infra();
        }
    }

    public function room()
    {
        $view = new View('admin_room');
        $view->display();
    }

    public function add_floor()
    {
        if (!empty($_POST['floor_name']) && !empty($_POST['gebaeude_select']) && !empty($_POST['floor_number'])) {
            $buildModel = new GebaeudeModel();

            try {
                $buildModel->addStockwerk(htmlspecialchars($_POST['floor_name']), htmlspecialchars($_POST['gebaeude_select']), htmlspecialchars($_POST['floor_number']));
            } catch (Exception $e) {
                $message[] = 'Konnte nicht erstellt werden!';
                $this->message = $message;
                $this->infra();
            }
        } else {
            $message[] = 'Bitte alle Felder ausfüllen!';
            $this->message = $message;
            $this->infra();
        }
    }

    public function edit_user()
    {

        if (!empty($_POST['user_id'])) {
            $view = new View('admin_edit');

            $userModel = new UserModel();
            $userTypeModel = new UsertypeModel();

            $view->userData = $userModel->readById($_POST['user_id']);
            $view->usertypes = $userTypeModel->readAll();

            $view->display();
        } else {
            $this->new_user();
        }
    }

    public function check_edit_user() {
        $userModel = new UserModel();

        if (!empty($_POST['edit_username'])) {

        }
    }

    public function delete_selected_users()
    {
        if (!empty($_POST['users'])) {
            $userModel = new UserModel();

            foreach ($_POST['users'] as $users) {
                $userModel->deleteById($users);
            }

            header('Location: /admin/index');
        }
    }

    public function delete_selected_klassen()
    {
        if (!empty($_POST['klassen'])) {
            $klassenModel = new KlassenModel();

            foreach ($_POST['klassen'] as $klasse) {
                $klassenModel->deleteById($klasse);
            }

            header('Location: /admin/index');
        }
    }

    public function classes() {
        $view = new View('admin_classes');

        $klassenModel = new KlassenModel();
        $userModel = new UserModel();
        $abteilungsModel = new AbteilungsModel();

        $view->klassen = $klassenModel->readAll();
        $view->lehrer = $userModel->readAllProfs();
        $view->abteilungen = $abteilungsModel->readAll();
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
        $usertypeModel = new UsertypeModel();
        try {
            $usertypes = $usertypeModel->readAll();
        } catch (Exception $e) {
            $usertypes = null;
        }

        $view = new View('admin_index');
        $view->users = $result;
        $view->usertypes = $usertypes;
        $view->display();

    }

    public function create_user()
    {
        if (!empty($_POST['new_username']) && !empty($_POST['new_password']) && !empty($_POST['new_vorname']) && !empty($_POST['new_nachname']) && !empty($_POST['usertype_select'])) {
            $userModel = new UserModel();
            $checkEmail = $userModel->check_if_email_exists($_POST['new_username']);
            if ($checkEmail->num_rows == 0) {
                if (!empty($_POST['pw_checkbox']) && $_POST['pw_checkbox'] == 'on') {
                    $userModel->create_without_hash($_POST['new_vorname'], $_POST['new_nachname'], $_POST['new_username'], $_POST['new_password'], $_POST['usertype_select'], 1);
                } else {
                    $userModel->create_with_hash($_POST['new_vorname'], $_POST['new_nachname'], $_POST['new_username'], $_POST['new_password'], $_POST['usertype_select'], 0);
                }
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

    public function new_user()
    {
        $view = new View('admin_new_user');

        $usertypeModel = new UsertypeModel();

        $view->usertypes = $usertypeModel->readAll();
        $view->display();
    }

    public function new_klasse()
    {
        $view = new View('admin_new_klasse');

        $userModel = new UserModel();
        $abteilungsModel = new AbteilungsModel();

        $view->lehrer = $userModel->readAllProfs();
        $view->abteilungen = $abteilungsModel->readAll();
        $view->display();
    }

    public function create_klasse() {
        if (!empty($_POST['new_klassenname']) && !empty($_POST['abteilungs_select']) && !empty($_POST['klassen_lp_select'])) {
            $klassenModel = new KlassenModel();

            try {
                $klassenModel->createKlasse(htmlspecialchars($_POST['new_klassenname']), htmlspecialchars($_POST['klassen_lp_select']), htmlspecialchars($_POST['abteilungs_select']));
                $this->classes();
            } catch (Exception $e) {
                $message[] = "Die Klasse konnte nicht erstellt werden!";
                $this->message = $message;
                $this->new_klasse();
            }
        } else {
            $message[] = "Bitte alle Felder ausfüllen!";
            $this->message = $message;
            $this->new_klasse();
        }
    }

    public function update_klasse() {
        if (!empty($_POST['edit_klassenname']) && !empty($_POST['edit_abteilungs_select']) && !empty($_POST['edit_klassen_lp_select'])) {
            $klassenModel = new KlassenModel();

            try {
                $klassenModel->createKlasse(htmlspecialchars($_POST['new_klassenname']), htmlspecialchars($_POST['klassen_lp_select']), htmlspecialchars($_POST['abteilungs_select']));
                $this->classes();
            } catch (Exception $e) {
                $message[] = "Die Klasse konnte nicht erstellt werden!";
                $this->message = $message;
                $this->new_klasse();
            }
        } else {
            $message[] = "Bitte alle Felder ausfüllen!";
            $this->message = $message;
            $this->new_klasse();
        }
    }

    public function __destruct()
    {
        $view = new View('footer');
        $view->message = $this->message;
        $view->display();
    }
}