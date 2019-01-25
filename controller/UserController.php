<?php

require_once('model/UserModel.php');
require_once('model/NachrichtenModel.php');
require_once('model/KlassenModel.php');
require_once('model/LektionenModel.php');
require_once('model/DatesModel.php');
require_once('model/GebaeudeModel.php');

/**
 * Siehe Dokumentation im DefaultController.
 */
class UserController
{

    private $message;

    /** Start */
    public function __construct()
    {
        $view = new View('header', array('title' => 'Benutzer', 'heading' => 'Benutzer'));
        $view->display();
    }

    public function logout()
    {
        session_destroy();
        header('Location: /');
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
                                header("Location: /user/index");
                                break;
                            case 3:
                                header("Location: /user/index");
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

    public function index()
    {
        $view = new View('user_index');

        $lesionModel = new LektionenModel();
        $klassenModel = new KlassenModel();
        $dateIds = array();

        switch ($_SESSION['userType']['id']) {
            case 2:
                $lesions = $lesionModel->get_lektionen_by_lp($_SESSION['user']['id']);
                foreach ($lesions as $lesion) {
                    $dateIds[] = $lesion->date_id;
                }
                $daten = $lesionModel->get_date_by_lektionen($dateIds);
                break;
            case 3:
                $klassenIDs = $klassenModel->get_klassenID_by_student($_SESSION['user']['id']);
                $lesions = $lesionModel->get_lektionen_by_klassen_ids($klassenIDs);
                foreach ($lesions as $lesion) {
                    $dateIds[] = $lesions->date_id;
                }
                $daten = $lesionModel->get_date_by_lektionen($dateIds);
                break;
            default:

        }

        $view->daten = $daten;
        $view->lektionen = $lesions;
        $view->display();
    }

    /** Login */
    public function login()
    {
        $view = new View('user_login');
        $view->display();
    }

    public function new_password()
    {
        $view = new View('user_password');

        $view->display();
    }

    /** Views */
    public function lesions()
    {
        $view = new View('user_lesions');
        $view->display();
    }

    public function create()
    {
        $view = new View('user_create');
        $view->title = 'Benutzer erstellen';
        $view->heading = 'Benutzer erstellen';
        $view->display();
    }

    public function edit_message()
    {
        if (!empty($_POST['nachrichten_id'])) {
            $nachrichtenModel = new NachrichtenModel();
            $klassenModel = new KlassenModel();
            $lektionenModel = new LektionenModel();
            $view = new View('user_edit_message');

            $view->klassen = $klassenModel->getKlassenByLehrerID($_SESSION['user']['id']);
            $view->lektionen = $lektionenModel->get_lektionen_by_lp($_SESSION['user']['id']);
            $view->nachricht = $nachrichtenModel->readById($_POST['nachrichten_id']);
            $view->display();
        } else {
            $this->new_message();
        }
    }

    public function new_message()
    {
        $view = new View('user_new_message');

        $lektionModel = new LektionenModel();
        $klassenModel = new KlassenModel();

        $view->lektionen = $lektionModel->get_lektionen_by_lp($_SESSION['user']['id']);
        $view->klassen = $klassenModel->getKlassenByLehrerID($_SESSION['user']['id']);

        $view->display();
    }

    public function create_message()
    {
        if (!empty($_POST['new_title']) && !empty($_POST['new_message_text'])) {
            $nachrichtenModel = new NachrichtenModel();
            $klasse = null;
            $lektion = null;
            $date = date('y-m-d');

            if (isset($_POST['klassen_select']) && !empty($_POST['klassen_select'])) {
                $klasse = $_POST['klassen_select'];
            }
            if (isset($_POST['lektion_select']) && !empty($_POST['lektion_select'])) {
                $lektion = $_POST['lektion_select'];
            }

            $nachrichtenModel->create(htmlspecialchars($_POST['new_title']), htmlspecialchars($_POST['new_message_text']), $date, $_SESSION['user']['id'], $klasse, $lektion);

            $this->messages();
        }
    }

    public function messages()
    {
        $view = new View('user_messages');
        $nachrichtenModel = new NachrichtenModel();
        $klassenModel = new KlassenModel();
        $lektionenModel = new LektionenModel();
        $userModel = new UserModel();
        /** Lehrperson */
        if ($_SESSION['userType']['id'] == 2) {
            $view->nachrichten = $nachrichtenModel->get_message_by_creator_sorted($_SESSION['user']['id']);
            $view->klassen = $klassenModel->getKlassenByLehrerID($_SESSION['user']['id']);
            $view->lektionen = $lektionenModel->get_lektionen_by_lp($_SESSION['user']['id']);
        }
        /** Lernende */
        if ($_SESSION['userType']['id'] == 3) {
            $user_klassen = $klassenModel->get_klassenID_by_student($_SESSION['user']['id']);
            $klassenIds = array();
            foreach ($user_klassen as $user_klasse) {
                $klassenIds[] = $user_klasse->klassen_id;
            }
            $lektionen = array();
            foreach ($klassenIds as $klassenID) {
                $lektionen[] = $lektionenModel->get_lektionen_by_klasse($klassenID);
            }
            $lektionIds = array();
            foreach ($lektionen as $lektion) {
                foreach ($lektion as $row) {
                    $lektionIds[] = $row->id;
                }
            }


            $view->klassen = $klassenModel->get_multiple_klassen_by_id($klassenIds);
            $view->lektionen = $lektionen;
            $view->nachrichten = $nachrichtenModel->get_message_for_student_sorted($klassenIds, $lektionIds);
            $view->teachers = $userModel->readAllProfs();
        }
        $view->display();
    }

    public function update_message()
    {
        if (!empty($_POST['edit_title']) && !empty($_POST['edit_message_text']) && !empty($_POST['edit_nachricht_id'])) {
            $nachrichtenModel = new NachrichtenModel();
            $klasse = null;
            $lektion = null;

            $date = date('y-m-d');

            if (isset($_POST['edit_klassen_select']) && !empty($_POST['edit_klassen_select'])) {
                $klasse = $_POST['edit_klassen_select'];
            }
            if (isset($_POST['edit_lektion_select']) && !empty($_POST['edit_lektion_select'])) {
                $lektion = $_POST['edit_lektion_select'];
            }
            $nachrichtenModel->update($_POST['edit_nachricht_id'], htmlspecialchars($_POST['edit_title']), htmlspecialchars($_POST['edit_message_text']), $date, $_SESSION['user']['id'], $klasse, $lektion);

            $this->messages();
        }
    }

    public function upload_plan()
    {
        $view = new View('prof_upload');

        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && $_SESSION['userType']['id'] == 2) {
            $klassenModel = new KlassenModel();
            $gebaeudeModel = new GebaeudeModel();

            $view->klassen = $klassenModel->getKlassenByLehrerID($_SESSION['user']['id']);
            $view->zimmerList = $gebaeudeModel->readAllRooms();
            $view->stockwerkeZimmer = $gebaeudeModel->readAllConnections();
            $view->stockwerke = $gebaeudeModel->readAllFloors();
            $view->buildings = $gebaeudeModel->readAll();
        } else {
            header('Location: /user/login');
        }

        $view->display();
    }
    /** Functions */
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

    public function delete()
    {
        $userRepository = new UserModel();
        $userRepository->deleteById($_GET['id']);

        // Anfrage an die URI /user weiterleiten (HTTP 302)
        header('Location: /user');
    }

    public function check_upload()
    {
        if (isset($_POST['upload']) && !empty($_POST['start_time']) && !empty($_POST['end_time']) && !empty($_POST['klassen_select']) && !empty(htmlspecialchars($_POST['lesion_title'])) && $_POST['zimmer_select']) {
            $uploadDir = 'data/uploads/';
            $uploadFile = $uploadDir . basename($_FILES['userfile']['name']);
            $lektionModel = new LektionenModel();

            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile)) {
                $row = 0;
                if (($handle = fopen($uploadFile, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if ($row > 0) {
                            $date = strtotime($data[1]);
                            $date = date('Y-m-d', $date);
                            $dateID = $lektionModel->create_new_date($date, $date, htmlspecialchars($_POST['start_time']), htmlspecialchars($_POST['end_time']), 0);
                            if (!empty($dateID)) {
                                $lektionModel->create_new_lesion($_POST['klassen_select'], $_SESSION['user']['id'], htmlspecialchars($_POST['lesion_title']), $data[2], $data[3], $dateID, $_POST['zimmer_select']);
                            }
                        }
                        $row++;
                    }
                    fclose($handle);
                    $message[] = 'Der Plan wurde angenommen und verarbeitet.';
                    $this->message = $message;
                    $this->index();
                }
            } else {
                $message[] = 'Der Plan konnte nicht hochgeladen werden.';
                $this->message = $message;
                $this->upload_plan();
            }
        }
    }

    /** End */
    public function __destruct()
    {
        $view = new View('footer');
        $view->message = $this->message;
        $view->display();
    }
}
