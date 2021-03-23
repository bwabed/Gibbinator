<?php


namespace App\Controller;


use App\Authentication\Authentication;
use App\Authentication\UserTypes;
use App\Repository\GebaeudeRepository;
use App\Repository\KlassenRepository;
use App\Repository\UserRepository;
use App\Repository\UserTypeRepository;
use App\View\View;
use Exception;

class AdminController
{

    private $message;

    public function index()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        $userRepo = new UserRepository();
        try {
            $result = $userRepo->readAllExceptLoggedIn($_SESSION['user']['id']);
        } catch (Exception $e) {
            $result = null;
        }
        $usertypeRepo = new UserTypeRepository();
        try {
            $usertypes = $usertypeRepo->readAll();
        } catch (Exception $e) {
            $usertypes = null;
        }

        $view = new View('admin/index');
        $view->users = $result;
        $view->usertypes = $usertypes;
        $view->message = $this->message;
        $view->display();
    }

    /** Infrastruktur */
    public function add_building()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        if (!empty($_POST['build_name']) && !empty($_POST['build_street']) && !empty($_POST['build_number']) && !empty($_POST['build_plz']) && !empty($_POST['build_ort'])) {
            $buildRepo = new GebaeudeRepository();
            try {
                $buildRepo->addBuilding($_POST['build_name'], $_POST['build_street'], $_POST['build_number'], $_POST['build_plz'], $_POST ['build_ort']);
                $this->message = ['Erfolgreich erstellt!'];
                unset($_POST);
                $this->infra();
            } catch (Exception $e) {
                $this->message = ['Konnte nicht eingefügt werden!'];
                $this->infra();
            }
        } else {
            $this->message = ['Bitte alle Felder ausfüllen!'];
            $this->infra();
        }
    }

    public function infra()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        $gebaeudeRepo = new GebaeudeRepository();
        try {
            $builds = $gebaeudeRepo->readAll();
        } catch (Exception $e) {
            $builds = null;
        }

        try {
            $floors = $gebaeudeRepo->readAllFloors();
        } catch (Exception $e) {
            $floors = null;
        }

        try {
            $rooms = $gebaeudeRepo->readAllRooms();
        } catch (Exception $e) {
            $rooms = null;
        }

        $view = new View('admin/infra');
        $view->gebaeude = $builds;
        $view->stockwerke = $floors;
        $view->zimmer = $rooms;
        $view->message = $this->message;
        $view->display();
    }

    public function edit_room()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        if (!empty($_POST['floor_select']) && !empty($_POST['room_name'])) {

            if (!empty($_POST['room_opt'])) {
                $optText = htmlspecialchars($_POST['room_opt']);
            } else {
                $optText = null;
            }

            $gebaeudeRepo = new GebaeudeRepository();

            try {
                $gebaeudeRepo->addRoom(htmlspecialchars($_POST['room_name']), $_POST['floor_select'], $optText);
                $this->message = ["Zimmer wurde erstellt."];
                unset($_POST);
                $this->infra();
            } catch (Exception $e) {
                $this->message = ["Zimmer wurde nicht erstellt."];
                $this->infra();
            }
        } else {
            $this->message = ['Bitte alle Felder ausfüllen'];
            $this->infra();
        }
    }

    public function add_room()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        if (!empty($_POST['room_name']) && !empty($_POST['room_gebaeude_select'])) {

            $roomData = ['name' => htmlspecialchars($_POST['room_name']), 'gebaeude_id' => urldecode($_POST['room_gebaeude_select'])];

            $gebaeudeRepo = new GebaeudeRepository();

            $view = new View('admin/room');

            $view->floors = $gebaeudeRepo->get_floors_by_gebaeude_id($_POST['room_gebaeude_select']);
            $view->roomData = $roomData;
            $this->message = ["Stockwerk und optionale Bezeichnung angeben."];
            $view->message = $this->message;
            $view->display();
        } else {
            $this->message = ['Bitte alle Felder ausfüllen!'];
            $this->infra();
        }
    }

    public function add_floor()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        if (!empty($_POST['floor_name']) && !empty($_POST['gebaeude_select']) && !empty($_POST['floor_number'])) {
            $buildRepo = new GebaeudeRepository();

            try {
                $buildRepo->addStockwerk(htmlspecialchars($_POST['floor_name']), htmlspecialchars($_POST['gebaeude_select']), htmlspecialchars($_POST['floor_number']));
                $this->message = ['Erfolgreich erstellt'];
                unset($_POST);
                $this->infra();
            } catch (Exception $e) {
                $this->message = ['Konnte nicht erstellt werden!'];
                $this->infra();
            }
        } else {
            $this->message = ['Bitte alle Felder ausfüllen!'];
            $this->infra();
        }
    }

    public function delete_selected_buildings()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        if (!empty($_POST['buildings'])) {
            $buildingRepo = new GebaeudeRepository();

            foreach ($_POST['buildings'] as $build) {
                $floors = $buildingRepo->get_floors_by_gebaeude_id($build);
                foreach ($floors as $floor) {
                    $buildingRepo->deleteRoomByStockwerkId($floor->id);

                }
                $buildingRepo->deleteById($build);
            }
            $this->message = ['Gebaeude gelöscht'];
            $this->infra();
        }
    }

    public function delete_selected_rooms()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        if (!empty($_POST['rooms'])) {
            $gebaeudeRepo = new GebaeudeRepository();

            foreach ($_POST['rooms'] as $room) {
                $gebaeudeRepo->deleteRoomById($room);
            }
            $this->message = ['Zimmer gelöscht'];
            $this->infra();
        }
    }

    public function delete_selected_floors()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        if (!empty($_POST['floors'])) {
            $gebaeudeRepo = new GebaeudeRepository();

            foreach ($_POST['floors'] as $floor) {
                $gebaeudeRepo->deleteRoomByStockwerkId($floor->id);
                $gebaeudeRepo->deleteFloorById($floor);
            }
            $this->message = ['Stockwerke gelöscht'];
            $this->infra();
        }
    }

    /** User Stuff */
    public function edit_user()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        if (!empty($_POST['user_id'])) {
            $view = new View('admin/edit_user');

            $userRepo = new UserRepository();
            $userTypeRepo = new UserTypeRepository();

            $view->userData = $userRepo->readById($_POST['user_id']);
            $view->usertypes = $userTypeRepo->readAll();

            $view->message = $this->message;
            $view->display();
        } else {
            $this->message = ['Etwas ist schief gelaufen'];
            $this->new_user();
        }
    }

    public function check_edit_user()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        $userRepo = new UserRepository();

        $email = $_POST['edit_username'];
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (!empty($_POST['edit_user_id']) && !empty($_POST['edit_username']) && !empty($_POST['edit_password']) && !empty($_POST['edit_vorname']) && !empty($_POST['edit_nachname']) && !empty($_POST['edit_usertype_select']) && !empty($_POST['edit_pw_checkbox'])) {
                $userRepo->update_with_pw($_POST['edit_user_id'], htmlspecialchars($_POST['edit_username']), htmlspecialchars($_POST['edit_password']), htmlspecialchars($_POST['edit_vorname']), htmlspecialchars($_POST['edit_nachname']), $_POST['edit_usertype_select'], $_POST['edit_pw_checkbox']);

                $this->message = ["Benutzer angepasst."];
                $this->index();
            } elseif (!empty($_POST['edit_user_id']) && !empty($_POST['edit_username']) && !empty($_POST['edit_vorname']) && !empty($_POST['edit_nachname']) && !empty($_POST['edit_usertype_select'])) {
                $userRepo->update_without_pw($_POST['edit_user_id'], htmlspecialchars($_POST['edit_username']), htmlspecialchars($_POST['edit_vorname']), htmlspecialchars($_POST['edit_nachname']), $_POST['edit_usertype_select']);

                $this->message = ["Benutzer angepasst."];
                $this->index();
            } else {
                $this->message = ["Update fehlgeschlagen!"];
                $this->index();
            }
        } else {
            $this->message = ["Email nicht valid!"];
            $this->index();
        }
    }

    public function delete_selected_users()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        if (!empty($_POST['users'])) {
            $userRepo = new UserRepository();

            foreach ($_POST['users'] as $user) {
                $userRepo->deleteById($user);
            }
            $this->message = ['Benutzer gelöscht'];
            $this->index();
        }
    }

    public function create_user()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        if (!empty($_POST['new_username']) && !empty($_POST['new_password']) && !empty($_POST['new_vorname']) && !empty($_POST['new_nachname']) && !empty($_POST['usertype_select'])) {
            $userRepo = new UserRepository();
            $email = $_POST['new_username'];
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $checkEmail = $userRepo->check_if_email_exists($_POST['new_username']);
                if ($checkEmail->num_rows == 0) {
                    if (!empty($_POST['pw_checkbox']) && $_POST['pw_checkbox'] == 'on') {
                        $userRepo->create_without_hash($_POST['new_vorname'], $_POST['new_nachname'], $_POST['new_username'], $_POST['new_password'], $_POST['usertype_select'], 1);
                    } else {
                        $userRepo->create_with_hash($_POST['new_vorname'], $_POST['new_nachname'], $_POST['new_username'], $_POST['new_password'], $_POST['usertype_select'], 0);
                    }
                    $this->message = ['Benutzer wurde erstellt.'];
                    $this->index();
                } else {
                    $this->message = ['Email ist schon vergeben!'];
                    $this->new_user();
                }
            } else {
                $this->message = ['Email nicht valid!'];
                $this->new_user();
            }

        } else {
            $this->message = ['Bitte alle Angaben ausfüllen!'];
            $this->new_user();
        }
    }

    public function new_user()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        $view = new View('admin/new_user');

        $usertypeRepo = new UserTypeRepository();

        $view->usertypes = $usertypeRepo->readAll();
        $view->message = $this->message;
        $view->display();
    }

    /** Klassen Stuff */
    public function update_klasse()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        if (!empty($_POST['edit_klassenname']) && !empty($_POST['edit_klassen_lp_select'])) {
            $klassenRepo = new KlassenRepository();

            try {
                $klassenRepo->updateKlasse(htmlspecialchars($_POST['edit_klassen_id']), htmlspecialchars($_POST['edit_klassenname']), htmlspecialchars($_POST['edit_klassen_lp_select']));
                $this->classes();
            } catch (Exception $e) {
                $this->message = ["Die Klasse konnte nicht erstellt werden!"];
                $this->new_klasse();
            }
        } else {
            $this->message = ["Bitte alle Felder ausfüllen!"];
            $this->new_klasse();
        }
    }

    public function classes()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        $view = new View('admin/classes');

        $klassenRepo = new KlassenRepository();
        $userRepo = new UserRepository();

        $view->klassen = $klassenRepo->readAll();
        $view->lehrer = $userRepo->readAllProfs();
        $view->message = $this->message;
        $view->display();
    }

    public function new_klasse()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        $view = new View('admin/new_klasse');

        $userRepo = new UserRepository();

        $view->lehrer = $userRepo->readAllProfs();
        $view->message = $this->message;
        $view->display();
    }

    public function delete_selected_klassen()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        if (!empty($_POST['klassen'])) {
            $klassenRepo = new KlassenRepository();

            foreach ($_POST['klassen'] as $klasse) {
                $klassenRepo->deleteById($klasse);
            }

            $this->message = ['Klassen gelöscht'];
            $this->classes();
        }
    }

    public function create_klasse()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        if (!empty($_POST['new_klassenname']) && !empty($_POST['klassen_lp_select'])) {
            $klassenRepo = new KlassenRepository();

            try {
                $klassenRepo->createKlasse(htmlspecialchars($_POST['new_klassenname']), htmlspecialchars($_POST['klassen_lp_select']));
                $this->classes();
            } catch (Exception $e) {
                $this->message = ["Die Klasse konnte nicht erstellt werden!"];
                $this->new_klasse();
            }
        } else {
            $this->message = ["Bitte alle Felder ausfüllen!"];
            $this->new_klasse();
        }
    }

    public function edit_klasse()
    {
        Authentication::restrict_authenticated(UserTypes::$_ADMIN);
        if (!empty($_POST['klassen_id'])) {

            $klassenRepo = new KlassenRepository();
            if (!empty($_POST['delete_users'])) {
                foreach ($_POST['delete_users'] as $user) {
                    $klassenRepo->delete_user_klasse_by_user($user);
                }
            }

            if (!empty($_POST['add_users'])) {
                $klassenID = $_POST['klassen_id'];
                foreach ($_POST['add_users'] as $user) {
                    $klassenRepo->create_user_klasse($klassenID, $user);
                }
            }

            $view = new View('admin/edit_klasse');

            $userRepo = new UserRepository();

            $klasse = $klassenRepo->readById($_POST['klassen_id']);
            $klassen_users = $klassenRepo->get_user_ids_of_klasse($_POST['klassen_id']);

            $lernende_in = array();
            $lernende_out = array();

            $ids = array();
            foreach ($klassen_users as $klassen_user) {
                $ids[] = $klassen_user->user_id;
            }

            if (!empty($ids)) {
                $lernende_in = $userRepo->get_multiple_user_by_id($ids);
            }

            if (!empty($lernende_in)) {
                $lernendeInIds = array();
                foreach ($lernende_in as $lern) {
                    $lernendeInIds[] = $lern->id;
                }
                if (!empty($lernendeInIds)) {
                    $lernende_out = $userRepo->readAllStudsExceptIds($lernendeInIds);
                }
            } else {
                $lernende_out = $userRepo->readAllStuds();
            }

            $view->klassen_lp = $userRepo->readById($klasse->klassen_lp);
            $view->lehrer = $userRepo->readAllProfs();
            $view->klasse = $klasse;
            $view->lernende_in = $lernende_in;
            $view->lernende_out = $lernende_out;
            $view->message = $this->message;
            $view->display();
        }
    }
}