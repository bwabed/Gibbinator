<?php

require_once('model/UserModel.php');
require_once('model/NachrichtenModel.php');
require_once('model/KlassenModel.php');
require_once('model/LektionenModel.php');
require_once('model/DatesModel.php');
require_once('model/GebaeudeModel.php');
require_once('model/FachModel.php');

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
                        $_SESSION ['user'] ['vorname'] = $row->vorname;
                        $_SESSION ['user'] ['nachname'] = $row->nachname;
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
                                header('Location: /user/index');
                                break;
                            case 3:
                                header('Location: /user/index');
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
                        $_SESSION ['user'] ['vorname'] = $row->vorname;
                        $_SESSION ['user'] ['nachname'] = $row->nachname;
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
                    } else {
                        $message[] = 'Login fehlgeschlagen!';
                        $this->message = $message;
                        $this->login();
                    }
                }

            } else {
                $message[] = 'Login fehlgeschlagen!';
                $this->message = $message;
                $this->login();
            }
        } else {
            $message[] = 'Login fehlgeschlagen!';
            $this->message = $message;
            $this->login();
        }
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

    public function create()
    {
        $view = new View('user_create');
        $view->title = 'Benutzer erstellen';
        $view->heading = 'Benutzer erstellen';
        $view->display();
    }

    public function edit_message()
    {
        $nachrichtenModel = new NachrichtenModel();
        $klassenModel = new KlassenModel();
        $lektionenModel = new LektionenModel();
        $fachModel = new FachModel();
        if (!empty($_POST['nachrichten_id'])) {
            $lektion = null;
            $view = new View('user_edit_message');

            $nachricht = $nachrichtenModel->readById($_POST['nachrichten_id']);
            $faecher = $fachModel->get_faecher_by_lehrer_id($_SESSION['user']['id']);
            if ($nachricht->lektion_id != null) {
                $lektion = $lektionenModel->readById($nachricht->lektion_id);
            }
            $view->klassen = $klassenModel->getKlassenByLehrerID($_SESSION['user']['id']);
            $view->faecher = $faecher;
            $view->lektion = $lektion;
            $view->nachricht = $nachricht;

            $view->display();
        } else {
            $this->message = ['Keine Nachricht ausgewählt'];
            $this->new_message();
        }
    }

    public function new_message()
    {
        $view = new View('user_new_message');

        $lektionModel = new LektionenModel();
        $klassenModel = new KlassenModel();
        $fachModel = new FachModel();
        $dateModel = new DatesModel();

        $faecher = $fachModel->get_faecher_by_lehrer_id($_SESSION['user']['id']);
        $lektion = null;
        if (isset($_POST['lektion_id']) && !empty($_POST['lektion_id'])) {
            $lektion = $lektionModel->readById($_POST['lektion_id']);
            $view->date = $dateModel->readById($lektion->date_id);
        }

        $view->faecher = $faecher;
        $view->lektion = $lektion;
        $view->klassen = $klassenModel->getKlassenByLehrerID($_SESSION['user']['id']);

        $view->display();
    }

    public function update_message()
    {
        if (!empty($_POST['edit_title']) && !empty($_POST['edit_message_text']) && !empty($_POST['edit_nachricht_id'])) {
            $nachrichtenModel = new NachrichtenModel();
            $klasse = null;
            $lektion = null;
            $fach = null;

            $date = date('y-m-d');

            if (isset($_POST['edit_klassen_select']) && !empty($_POST['edit_klassen_select'])) {
                $klasse = $_POST['edit_klassen_select'];
            }
            if (isset($_POST['edit_fach_select']) && !empty($_POST['edit_fach_select'])) {
                $fach = $_POST['edit_fach_select'];
            }
            $nachrichtenModel->update($_POST['edit_nachricht_id'], htmlspecialchars($_POST['edit_title']), htmlspecialchars($_POST['edit_message_text']), $date, $_SESSION['user']['id'], $klasse, $lektion, $fach);

            $this->message = ['Nachricht gespeichert'];
            $this->messages();
        } else {
            $this->message = ['Nachricht nicht gespeichert'];
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
        $fachModel = new FachModel();
        $dateModel = new DatesModel();
        $fachIds = array();
        $view->dates = array();
        $lektionen = array();
        $nachrichten = array();
        $faecher = array();
        $klassen = array();
        /** Lehrperson */
        if ($_SESSION['userType']['id'] == 2) {
            $nachrichten = $nachrichtenModel->get_message_by_creator_sorted($_SESSION['user']['id']);
            $klassen = $klassenModel->getKlassenByLehrerID($_SESSION['user']['id']);
            $faecher = $fachModel->get_faecher_by_lehrer_id($_SESSION['user']['id']);
            foreach ($faecher as $fach) {
                $fachIds[] = $fach->id;
            }
            if (!empty($fachIds)) {
                $lektionen = $lektionenModel->get_lektionen_by_faecher($fachIds);
            }
            $dateIds = array();
            foreach ($lektionen as $lektion) {
                $dateIds[] = $lektion->date_id;
            }
            if (!empty($dateIds)) {
                $view->dates = $dateModel->get_dates_with_ids($dateIds);
            }
        }
        /** Lernende */
        if ($_SESSION['userType']['id'] == 3) {
            $user_klassen = $klassenModel->get_klassenID_by_student($_SESSION['user']['id']);
            $klassenIds = array();
            foreach ($user_klassen as $user_klasse) {
                $klassenIds[] = $user_klasse->klassen_id;
            }
            if (!empty($klassenIds)) {
                $faecher = $fachModel->get_faecher_by_klassen($klassenIds);
            }

            foreach ($faecher as $fach) {
                $fachIds[] = $fach->id;
            }
            if (!empty($fachIds)) {
                $lektionen = $lektionenModel->get_lektionen_by_faecher($fachIds);
            }
            $dateIds = array();
            foreach ($lektionen as $lektion) {
                $dateIds[] = $lektion->id;
            }
            if (!empty($klassenIds)) {
                $klassen = $klassenModel->get_multiple_klassen_by_id($klassenIds);
            }
            $nachrichten = $nachrichtenModel->get_message_for_student_sorted($klassenIds, $dateIds, $fachIds);
            $view->teachers = $userModel->readAllProfs();
        }

        $view->faecher = $faecher;
        $view->lektionen = $lektionen;
        $view->nachrichten = $nachrichten;
        $view->klassen = $klassen;
        $view->display();
    }

    public function lesion_detail()
    {
        if ($_SESSION['userType']['id'] == 2) {
            $view = new View('user_edit_lesion');
        } else {
            $view = new View('user_lesion_detail');
        }
        $lesionModel = new LektionenModel();
        $nachrichtenModel = new NachrichtenModel();
        $dateModel = new DatesModel();
        $userModel = new UserModel();
        $buildModel = new GebaeudeModel();
        $fachModel = new FachModel();

        if (isset($_GET['lesion_id']) && !empty($_GET['lesion_id'])) {
            $lesion = $lesionModel->readById($_GET['lesion_id']);
            $date = $dateModel->readById($lesion->date_id);
            $nachrichten = $nachrichtenModel->get_message_for_lesion_sorted($_GET['lesion_id']);
            $fach = $fachModel->readById($lesion->fach_id);
            $user = $userModel->readById($fach->lehrer_id);
            $zimmer = $buildModel->get_room_by_id($lesion->zimmer);
            $stockwerk = $buildModel->get_floor_by_id($zimmer->stockwerk_id);
            $build = $buildModel->readById($stockwerk->gebaeude_id);
        }

        $view->fach = $fach;
        $view->zimmer = $zimmer;
        $view->stockwerk = $stockwerk;
        $view->build = $build;
        $view->user = $user;
        $view->date = $date;
        $view->nachrichten = $nachrichten;
        $view->lesion = $lesion;
        $view->display();
    }

    public function edit_lesion()
    {
        $view = new View('user_edit_lesion');
        $lesionModel = new LektionenModel();
        $nachrichtenModel = new NachrichtenModel();
        $dateModel = new DatesModel();
        $userModel = new UserModel();
        $buildModel = new GebaeudeModel();
        $fachModel = new FachModel();

        if (isset($_GET['lesion_id']) && !empty($_GET['lesion_id'])) {
            $lesion = $lesionModel->readById($_GET['lesion_id']);
            $date = $dateModel->readById($lesion->date_id);
            $nachrichten = $nachrichtenModel->get_message_for_lesion_sorted($_GET['lesion_id']);
            $fach = $fachModel->readById($lesion->fach_id);
            $user = $userModel->readById($fach->lehrer_id);
            $zimmer = $buildModel->get_room_by_id($lesion->zimmer);
            $stockwerk = $buildModel->get_floor_by_id($zimmer->stockwerk_id);
            $build = $buildModel->readById($stockwerk->gebaeude_id);
        }

        $view->fach = $fach;
        $view->zimmer = $zimmer;
        $view->stockwerk = $stockwerk;
        $view->build = $build;
        $view->user = $user;
        $view->date = $date;
        $view->nachrichten = $nachrichten;
        $view->lesion = $lesion;
        $view->display();
    }

    public function update_lesion()
    {
        $lesionModel = new LektionenModel();
        if (isset($_POST['delete_button']) && !empty($_POST['lektion_id'])) {
            $lesionModel->deleteById($_POST['lektion_id']);
            header('Location: /user/lesions');
        } elseif (isset($_POST['speichern_button']) && !empty($_POST['lektion_id'])) {

            $row = $lesionModel->update(htmlspecialchars($_POST['edit_prog_them']), htmlspecialchars($_POST['edit_term_aufg']), $_POST['lektion_id']);
            if ($row == 1) {
                $this->message = ['Lektion gespeichert'];
                $this->lesions();
            } else {
                header('Location: /user/edit_lesion?lesion_id=' . $_POST['lektion_id']);
            }
        } else {
            $this->lesions();
        }
    }

    /** Views */
    public function lesions()
    {
        $view = new View('user_lesions');

        $lektionenModel = new LektionenModel();
        $fachModel = new FachModel();
        $dateModel = new DatesModel();
        $klassenModel = new KlassenModel();
        $buildModel = new GebaeudeModel();
        $lektionen = array();
        $dates = array();
        $zimmer = array();
        $klassenIds = array();

        if ($_SESSION['userType']['id'] == 2) {

            $faecher = $fachModel->get_faecher_by_lehrer_id($_SESSION['user']['id']);
            $fachIds = array();
            foreach ($faecher as $fach) {
                $fachIds[] = $fach->id;
                $klassenIds[] = $fach->klassen_id;
            }
            $klassenLP = $klassenModel->getKlassenByLehrerID($_SESSION['user']['id']);
            foreach ($klassenLP as $lp) {
                $klassenIds[] = $lp->id;
            }
            if (!empty($fachIds)) {
                $lektionen = $lektionenModel->get_lektionen_by_faecher($fachIds);
            }
            $klassen = $klassenModel->get_multiple_klassen_by_id($klassenIds);
            $dateIds = array();
            $zimmerIds = array();
            foreach ($lektionen as $lektion) {
                $dateIds[] = $lektion->date_id;
                $zimmerIds[] = $lektion->zimmer;
            }
            if (!empty($dateIds)) {
                $dates = $dateModel->get_dates_with_ids($dateIds);
            }
            if (!empty($zimmerIds)) {
                $zimmer = $buildModel->get_rooms_by_ids($zimmerIds);
            }

        } elseif ($_SESSION['userType']['id'] == 3) {

            $userModel = new UserModel();
            $user_klassen = $klassenModel->get_klassenID_by_student($_SESSION['user']['id']);
            foreach ($user_klassen as $user_klasse) {
                $klassenIds[] = $user_klasse->klassen_id;
            }
            if (!empty($klassenIds)) {
                $klassen = $klassenModel->get_multiple_klassen_by_id($klassenIds);
                $faecher = $fachModel->get_faecher_by_klassen($klassenIds);
            }
            $fachIds = array();
            foreach ($faecher as $fach) {
                $fachIds[] = $fach->id;
            }
            if (!empty($fachIds)) {
                $lektionen = $lektionenModel->get_lektionen_by_faecher($fachIds);
            }
            $dateIds = array();
            $zimmerIds = array();
            foreach ($lektionen as $lektion) {
                $dateIds[] = $lektion->date_id;
                $zimmerIds[] = $lektion->zimmer;
            }
            if (!empty($dateIds)) {
                $dates = $dateModel->get_dates_with_ids($dateIds);
            }
            if (!empty($zimmerIds)) {
                $zimmer = $buildModel->get_rooms_by_ids($zimmerIds);
            }
            $view->profs = $userModel->readAllProfs();
        }

        $view->zimmer = $zimmer;
        $view->dates = $dates;
        $view->lektionen = $lektionen;
        $view->klassen = $klassen;
        $view->faecher = $faecher;
        $view->display();
    }

    public function edit_klasse()
    {
        if (!empty($_POST['klassen_id'])) {

            $lernende_out = array();
            $lernende_in = array();

            $klassenModel = new KlassenModel();
            if (!empty($_POST['delete_users'])) {
                foreach ($_POST['delete_users'] as $user) {
                    $klassenModel->delete_user_klasse_by_user($user);
                }
            }

            if (!empty($_POST['add_users'])) {
                $klassenID = $_POST['klassen_id'];
                foreach ($_POST['add_users'] as $user) {
                    $klassenModel->create_user_klasse($klassenID, $user);
                }
            }

            $view = new View('user_edit_klasse');

            $userModel = new UserModel();

            $klasse = $klassenModel->readById($_POST['klassen_id']);
            $klassen_users = $klassenModel->get_user_ids_of_klasse($_POST['klassen_id']);

            $ids = array();
            foreach ($klassen_users as $klassen_user) {
                $ids[] = $klassen_user->user_id;
            }
            if (!empty($ids)) {
                $lernende_in = $userModel->get_multiple_user_by_id($ids);
            }

            $lernendeInIds = array();
            foreach ($lernende_in as $lern) {
                $lernendeInIds[] = $lern->id;
            }
            if (!empty($lernendeInIds)) {
                $lernende_out = $userModel->readAllStudsExceptIds($lernendeInIds);
            } else {
                $lernende_out = $userModel->readAllStuds();
            }

            $view->klassen_lp = $userModel->readById($klasse->klassen_lp);
            $view->lehrer = $userModel->readAllProfs();
            $view->klasse = $klasse;
            $view->lernende_in = $lernende_in;
            $view->lernende_out = $lernende_out;
            $view->display();
        }
    }

    public function lehrer()
    {
        $view = new View('user_lehrer');
        $userModel = new UserModel();
        $fachModel = new FachModel();
        $klassenModel = new KlassenModel();

        $klassen = array();
        $faecher = array();

        $user_klassen = $klassenModel->get_klassenID_by_student($_SESSION['user']['id']);
        $klassenIds = array();
        foreach ($user_klassen as $user_klasse) {
            $klassenIds[] = $user_klasse->klassen_id;
        }

        if (!empty($klassenIds)) {
            $klassen = $klassenModel->get_multiple_klassen_by_id($klassenIds);
            $faecher = $fachModel->get_faecher_by_klassen($klassenIds);
        }

        $view->klassen = $klassen;
        $view->faecher = $faecher;
        $view->users = $userModel->readAllProfs();

        $view->display();
    }

    public function stud_klassen()
    {
        $view = new View('user_stud_klassen');

        $user = array();
        $klassen = array();
        $klassen_users = array();

        $klassenModel = new KlassenModel();
        $userModel = new UserModel();

        $user_klassen = $klassenModel->get_klassenID_by_student($_SESSION['user']['id']);
        $klassenIds = array();
        foreach ($user_klassen as $user_klasse) {
            $klassenIds[] = $user_klasse->klassen_id;
        }
        if (!empty($klassenIds)) {
            $klassen = $klassenModel->get_multiple_klassen_by_id($klassenIds);
            $klassen_users = $klassenModel->get_user_ids_of_multiple_klasse($klassenIds);
        }
        $userIds = array();
        foreach ($klassen_users as $klassen_user) {
            $userIds[] = $klassen_user->user_id;
        }
        if (!empty($userIds)) {
            $user = $userModel->get_multiple_user_by_id($userIds);
        }

        $view->profs = $userModel->readAllProfs();
        $view->users = $user;
        $view->klassenUser = $klassen_users;
        $view->klassen = $klassen;
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
                    switch ($_SESSION['userType']['id']) {
                        case 1:
                            header('Location: admin/index');
                            break;
                        case 2:
                            $this->index();
                            break;
                        case 3:
                            $this->index();
                            break;
                        default:
                            $this->login();
                    }
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
            $this->message = ['Bitte beide Passwörter angeben'];
            $this->edit_profile();
        }
    }

    public function index()
    {
        $view = new View('user_index');

        $lesionModel = new LektionenModel();
        $klassenModel = new KlassenModel();
        $fachModel = new FachModel();
        $messageModel = new NachrichtenModel();
        $userModel = new UserModel();
        $dateIds = array();
        $fachIds = array();
        $klassenIds = array();
        $lektionIds = array();
        $nachrichten = array();
        $faecher = array();
        $daten = array();
        $lesions = array();

        switch ($_SESSION['userType']['id']) {
            case 2:
                $faecher = $fachModel->get_faecher_by_lehrer_id($_SESSION['user']['id']);
                $klassen = $klassenModel->getKlassenByLehrerID($_SESSION['user']['id']);
                foreach ($faecher as $fach) {
                    $fachIds[] = $fach->id;
                }
                if (!empty($fachIds)) {
                    $lesions = $lesionModel->get_lektionen_by_faecher($fachIds);
                }
                foreach ($lesions as $lesion) {
                    $dateIds[] = $lesion->date_id;
                }
                if (!empty($dateIds)) {
                    $daten = $lesionModel->get_date_by_lektionen($dateIds);
                }
                $nachrichten = $messageModel->get_message_by_creator_sorted($_SESSION['user']['id'], 6);
                break;
            case 3:
                $userKlassen = $klassenModel->get_klassenID_by_student($_SESSION['user']['id']);
                foreach ($userKlassen as $userKlasse) {
                    $klassenIds[] = $userKlasse->klassen_id;
                }
                if (!empty($klassenIds)) {
                    $faecher = $fachModel->get_faecher_by_klassen($klassenIds);
                }
                foreach ($faecher as $fach) {
                    $fachIds[] = $fach->id;
                }
                if (!empty($fachIds)) {
                    $lesions = $lesionModel->get_lektionen_by_faecher($fachIds);
                }
                foreach ($lesions as $lesion) {
                    $dateIds[] = $lesion->date_id;
                    $lektionIds[] = $lesion->id;
                }
                if (!empty($klassenIds)) {
                    $klassen = $klassenModel->get_multiple_klassen_by_id($klassenIds);
                }
                if (!empty($dateIds)) {
                    $daten = $lesionModel->get_date_by_lektionen($dateIds);
                }
                $nachrichten = $messageModel->get_message_for_student_sorted($klassenIds, $lektionIds, $fachIds, 6);
                break;
            default:
                $nachrichten = array();
                $faecher = array();
                $daten = array();
                $lesions = array();
        }

        $view->klassen = $klassen;
        $view->profs = $userModel->readAllProfs();
        $view->nachrichten = $nachrichten;
        $view->faecher = $faecher;
        $view->daten = $daten;
        $view->lektionen = $lesions;
        $view->display();
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

    public function delete_selected_klassen()
    {
        if (!empty($_POST['klassen'])) {
            $klassenModel = new KlassenModel();

            foreach ($_POST['klassen'] as $klasse) {
                $klassenModel->deleteById($klasse);
            }

            $message[] = 'Klassen gelöscht';
            $this->message = $message;
            $this->klassen();
        }
    }

    public function klassen()
    {

        $klassenModel = new KlassenModel();
        $fachModel = new FachModel();
        $userModel = new UserModel();
        $view = new View('user_klassen');

        $faecher = array();
        $klassen = array();

        if ($_SESSION['userType']['id'] == 2) {
            $klassenLp = $klassenModel->getKlassenByLehrerID($_SESSION['user']['id']);
            $faecher = $fachModel->get_faecher_by_lehrer_id($_SESSION['user']['id']);
            $klassenIds = array();
            foreach ($klassenLp as $klasse) {
                $klassenIds[] = $klasse->id;
            }
            foreach ($faecher as $fach) {
                $klassenIds[] = $fach->klassen_id;
            }
            if (!empty($klassenIds)) {
                $klassen = $klassenModel->get_multiple_klassen_by_id($klassenIds);
            }
        }

        $view->allKlassen = $klassenModel->readAll();
        $view->lehrer = $userModel->readAllProfs();
        $view->klassen = $klassen;
        $view->faecher = $faecher;
        $view->allFaecher = $fachModel->readAll();
        $view->display();
    }

    public function create_klasse()
    {
        $klassenModel = new KlassenModel();
        if (!empty($_POST['new_klassenname'])) {
            try {
                $klassenModel->createKlasse(htmlspecialchars($_POST['new_klassenname']), $_POST['klassen_lp_select']);
                $this->message ['Klasse erstellt'];
                $this->klassen();
            } catch (Exception $e) {
                $message[] = "Die Klasse konnte nicht erstellt werden!";
                $this->message = $message;
                $this->klassen();
            }
        }
    }

    public function create_fach()
    {
        $fachModel = new FachModel();
        if (!empty($_POST['new_fachtitle']) && !empty($_POST['new_fach_lp_select']) && !empty($_POST['klassen_select'])) {
            try {
                $fachModel->create_new_fach(htmlspecialchars($_POST['new_fachtitle']), $_POST['klassen_select'], $_POST['new_fach_lp_select']);
                $this->message ['Fach erstellt'];
                $this->klassen();
            } catch (Exception $e) {
                $message[] = "Das Fach konnte nicht erstellt werden!";
                $this->message = $message;
                $this->klassen();
            }
        }
    }

    public function create_message()
    {
        if (!empty($_POST['new_title']) && !empty($_POST['new_message_text'])) {
            $nachrichtenModel = new NachrichtenModel();
            $klasse = null;
            $lektion = null;
            $fach = null;
            $date = date('y-m-d');

            if (isset($_POST['klassen_select']) && !empty($_POST['klassen_select'])) {
                $klasse = $_POST['klassen_select'];
            }
            if (isset($_POST['fach_select']) && !empty($_POST['fach_select'])) {
                $fach = $_POST['fach_select'];
            }
            if (!empty($_POST['lektion_id'])) {
                $lektion = $_POST['lektion_id'];
            }

            $nachrichtenModel->create(htmlspecialchars($_POST['new_title']), htmlspecialchars($_POST['new_message_text']), $date, $_SESSION['user']['id'], $klasse, $lektion, $fach);

            $this->message = ['Nachricht erstellt'];
            $this->messages();
        }
    }

    public function delete_selected_message()
    {
        $nachrichtenModel = new NachrichtenModel();
        if (isset($_POST['messages']) && !empty($_POST['messages']) && $_SESSION['userType']['id'] == 2) {
            foreach ($_POST['messages'] as $message) {
                $nachrichtenModel->deleteById($message);
            }
        }
        $this->message = ['Nachricht gelöscht'];
        $this->messages();
    }

    public function check_upload()
    {
        if (!empty($_POST['start_time']) && !empty($_POST['end_time']) && !empty($_POST['klassen_select']) && !empty(htmlspecialchars($_POST['fach_title'])) && !empty($_POST['zimmer_select'])) {
            $uploadDir = 'data/uploads/';
            $uploadFile = $uploadDir . basename($_FILES['userfile']['name']);
            $lektionModel = new LektionenModel();
            $fachModel = new FachModel();

            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile)) {
                $row = 0;
                $fachId = $fachModel->create_new_fach(htmlspecialchars($_POST['fach_title']), $_POST['klassen_select'], $_SESSION['user']['id']);
                if (($handle = fopen($uploadFile, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                        if ($row > 0) {
                            $date = strtotime($data[1]);
                            $date = date('Y-m-d', $date);
                            $startZeit = htmlspecialchars($_POST['start_time']) . ':00';
                            $endZeit = htmlspecialchars($_POST['end_time']) . ':00';

                            $dateID = $lektionModel->create_new_date($date, $date, $startZeit, $endZeit, 0);
                            if (!empty($dateID)) {
                                $lektionModel->create_new_lesion($data[2], $data[3], $dateID, $_POST['zimmer_select'], $fachId);
                            }
                        }
                        $row++;
                    }
                    fclose($handle);
                    $message[] = 'Der Plan wurde angenommen und verarbeitet.';
                    $this->message = $message;
                    $this->index();
                } else {
                    $message[] = 'Die Datei konnte nicht gelesen werden.';
                    $this->message = $message;
                    $this->upload_plan();
                }
            } else {
                $message[] = 'Der Plan konnte nicht hochgeladen werden.';
                $this->message = $message;
                $this->upload_plan();
            }
        }
    }

    public function upload_plan()
    {
        $view = new View('user_upload');

        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && $_SESSION['userType']['id'] == 2) {
            $klassenModel = new KlassenModel();
            $gebaeudeModel = new GebaeudeModel();

            $view->klassen = $klassenModel->readALL();
            $view->zimmerList = $gebaeudeModel->readAllRooms();
            $view->stockwerke = $gebaeudeModel->readAllFloors();
            $view->buildings = $gebaeudeModel->readAll();
        } else {
            header('Location: /user/login');
        }

        $view->display();
    }

    /** End */
    public function __destruct()
    {
        $view = new View('footer');
        $view->message = $this->message;
        $view->display();
    }
}
