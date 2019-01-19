<?php

require_once('model/UserModel.php');
require_once('model/UsertypeModel.php');
require_once('model/GebaeudeModel.php');
require_once('model/KlassenModel.php');

/**
 * Created by PhpStorm.
 * User: dimit
 * Date: 12.01.2019
 * Time: 11:57
 */
class AdminController
{
    private $message;

    /** Start */
    public function __construct()
    {
        $view = new View('header', array('title' => 'Startseite', 'heading' => 'Startseite'));
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

    /** Infrastruktur */
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
            $builds = $gebaeudeModel->readAll();
        } catch (Exception $e) {
            $builds = null;
        }

        try {
            $floors = $gebaeudeModel->readAllFloors();
        } catch (Exception $e) {
            $floors = null;
        }

        try {
            $rooms = $gebaeudeModel->readAllRooms();
        } catch (Exception $e) {
            $rooms = null;
        }

        try {
            $connections = $gebaeudeModel->readAllConnections();
        } catch (Exception $e) {
            $connections = null;
        }

        $view = new View('admin_infra');
        $view->gebaeude = $builds;
        $view->stockwerke = $floors;
        $view->zimmer = $rooms;
        $view->connections = $connections;
        $view->display();
    }

    public function edit_room()
    {
        if (!empty($_POST['floor_select']) && !empty($_POST['room_name']) && !empty($_POST['gebaeude_id'])) {

            if (!empty($_POST['room_opt'])) {
                $optText = htmlspecialchars($_POST['room_opt']);
            } else {
                $optText = null;
            }

            $gebaeudeModel = new GebaeudeModel();

            try {
                $zimmerID = $gebaeudeModel->addRoom(htmlspecialchars($_POST['room_name']), $optText);
                $gebaeudeModel->addConnectionRoomFloor($zimmerID, $_POST['floor_select']);

                header('Location: /admin/infra');
            } catch (Exception $e) {
                $message[] = "Zimmer wurde nicht erstellt.";
                $this->message = $message;
                $this->infra();
            }
        }
    }

    public function add_room()
    {
        if (!empty($_POST['room_name']) && !empty($_POST['room_gebaeude_select'])) {

            $roomData = ['name' => htmlspecialchars($_POST['room_name']), 'gebaueude_id' => $_POST['room_gebaeude_select']];

            $gebaeudeModel = new GebaeudeModel();

            $view = new View('admin_room');

            $view->floors = $gebaeudeModel->get_floors_by_gebaeude_id($_POST['room_gebaeude_select']);
            $view->roomData = $roomData;
            $message[] = "Stockwerk und optionale Bezeichnung angeben.";
            $this->message = $message;
            $view->display();
        } else {
            $message[] = 'Bitte alle Felder ausfüllen!';
            $this->message = $message;
            $this->infra();
        }
    }

    public function add_floor()
    {
        if (!empty($_POST['floor_name']) && !empty($_POST['gebaeude_select']) && !empty($_POST['floor_number'])) {
            $buildModel = new GebaeudeModel();

            try {
                $buildModel->addStockwerk(htmlspecialchars($_POST['floor_name']), htmlspecialchars($_POST['gebaeude_select']), htmlspecialchars($_POST['floor_number']));
                $this->infra();
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

    public function delete_selected_buildings()
    {
        if (!empty($_POST['buildings'])) {
            $buildingModel = new GebaeudeModel();

            foreach ($_POST['buildings'] as $build) {
                $floors = $buildingModel->get_floors_by_gebaeude_id($build);
                foreach ($floors as $floor) {
                    $connections = $buildingModel->get_connections_by_floor_id($floor->id);
                    foreach ($connections as $connection) {
                        $buildingModel->deleteRoomById($connection->zimmer_id);
                    }
                }
                $buildingModel->deleteById($build);
            }
            header('Location: /admin/infra');
        }
    }

    public function delete_selected_rooms()
    {
        if (!empty($_POST['rooms'])) {
            $gebaeudeModel = new GebaeudeModel();

            foreach ($_POST['rooms'] as $room) {
                $gebaeudeModel->deleteRoomById($room);
            }
            header('Location: /admin/infra');
        }
    }

    public function delete_selected_floors()
    {
        if (!empty($_POST['floors'])) {
            $gebaeudeModel = new GebaeudeModel();

            foreach ($_POST['floors'] as $floor) {
                $connections = $gebaeudeModel->get_connections_by_floor_id($floor);

                foreach ($connections as $connection) {
                    $gebaeudeModel->deleteRoomById($connection->zimmer_id);
                }
                $gebaeudeModel->deleteFloorById($floor);
            }
            header('Location: /admin/infra');
        }
    }

    /** User Stuff */
    public function edit_user()
    {

        if (!empty($_POST['user_id'])) {
            $view = new View('admin_edit_user');

            $userModel = new UserModel();
            $userTypeModel = new UsertypeModel();

            $view->userData = $userModel->readById($_POST['user_id']);
            $view->usertypes = $userTypeModel->readAll();

            $view->display();
        } else {
            $this->new_user();
        }
    }

    public function check_edit_user()
    {
        $userModel = new UserModel();

        if (!empty($_POST['edit_user_id']) && !empty($_POST['edit_username']) && !empty($_POST['edit_password']) && !empty($_POST['edit_vorname']) && !empty($_POST['edit_nachname']) && !empty($_POST['edit_usertype_select']) && !empty($_POST['edit_pw_checkbox'])) {
            $userModel->update_with_pw($_POST['edit_user_id'], htmlspecialchars($_POST['edit_username']), htmlspecialchars($_POST['edit_password']), htmlspecialchars($_POST['edit_vorname']), htmlspecialchars($_POST['edit_nachname']), $_POST['edit_usertype_select'], $_POST['edit_pw_checkbox']);

            $message[] = "Benutzer angepasst.";
            $this->message = $message;
            $this->index();
        } elseif (!empty($_POST['edit_user_id']) && !empty($_POST['edit_username']) && !empty($_POST['edit_vorname']) && !empty($_POST['edit_nachname']) && !empty($_POST['edit_usertype_select'])) {
            $userModel->update_without_pw($_POST['edit_user_id'], htmlspecialchars($_POST['edit_username']), htmlspecialchars($_POST['edit_vorname']), htmlspecialchars($_POST['edit_nachname']), $_POST['edit_usertype_select']);

            $message[] = "Benutzer angepasst.";
            $this->message = $message;
            $this->index();
        } else {
            $message[] = "Update fehlgeschlagen!";
            $this->message = $message;
            $this->index();
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

    /** Klassen Stuff */
    public function update_klasse()
    {
        if (!empty($_POST['edit_klassenname']) && !empty($_POST['edit_klassen_lp_select'])) {
            $klassenModel = new KlassenModel();

            try {
                $klassenModel->updateKlasse(htmlspecialchars($_POST['edit_klassen_id']), htmlspecialchars($_POST['edit_klassenname']), htmlspecialchars($_POST['edit_klassen_lp_select']));
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

    public function classes()
    {
        $view = new View('admin_classes');

        $klassenModel = new KlassenModel();
        $userModel = new UserModel();

        $view->klassen = $klassenModel->readAll();
        $view->lehrer = $userModel->readAllProfs();
        $view->display();
    }

    public function new_klasse()
    {
        $view = new View('admin_new_klasse');

        $userModel = new UserModel();

        $view->lehrer = $userModel->readAllProfs();
        $view->display();
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

    public function create_klasse()
    {
        if (!empty($_POST['new_klassenname']) && !empty($_POST['klassen_lp_select'])) {
            $klassenModel = new KlassenModel();

            try {
                $klassenModel->createKlasse(htmlspecialchars($_POST['new_klassenname']), htmlspecialchars($_POST['klassen_lp_select']));
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

    public function edit_klasse()
    {
        if (!empty($_POST['klassen_id'])) {

            if (!empty($_POST['delete_users'])) {
                $klassenModel = new KlassenModel();

                foreach ($_POST['delete_users'] as $user) {
                    $klassenModel->delete_user_klasse_by_user($user);
                }
            }

            if (!empty($_POST['add_users'])) {
                $klassenID = $_POST['klassen_id'];
                $klassenModel = new KlassenModel();
                foreach ($_POST['add_users'] as $user) {
                    $klassenModel->create_user_klasse($klassenID, $user);
                }
            }

            $view = new View('admin_edit_klasse');

            $klassenModel = new KlassenModel();
            $userModel = new UserModel();

            $klasse = $klassenModel->readById($_POST['klassen_id']);
            $klassen_users = $klassenModel->get_user_ids_of_klasse($_POST['klassen_id']);


            $rows = array();
            foreach ($klassen_users as $klassen_user) {
                $rows[] = $userModel->readById($klassen_user->user_id);
            }

            $view->klassen_lp = $userModel->readById($klasse->klassen_lp);
            $view->lehrer = $userModel->readAllProfs();
            $view->klasse = $klasse;
            $view->lernende = $rows;
            $view->display();
        }
    }

    public function user_klasse()
    {
        $view = new View('admin_user_class');
        $userModel = new UserModel();

        $lernende = $userModel->readAllStuds();
        $view->lernende = $lernende;
        $view->klassen_id = $_POST['klassen_id'];
        $view->display();
    }

    /** END */
    public function __destruct()
    {
        $view = new View('footer');
        $view->message = $this->message;
        $view->display();
    }
}