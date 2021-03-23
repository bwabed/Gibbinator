<?php

namespace App\Controller;

use App\Authentication\Authentication;
use App\Authentication\UserTypes;
use App\Repository\DatesRepository;
use App\Repository\FachRepository;
use App\Repository\GebaeudeRepository;
use App\Repository\KlassenRepository;
use App\Repository\LektionRepository;
use App\Repository\NachrichtRepository;
use App\Repository\UserRepository;
use App\View\View;
use Exception;

/**
 * Siehe Dokumentation im DefaultController.
 */
class UserController
{
    private $message;

    public function logout()
    {
        session_destroy();
        header('Location: /');
        die();
    }

    public function check_login()
    {
        $message = array();
        if (isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["password"])) {
            $username = htmlspecialchars($_POST["username"]);
            $password = htmlspecialchars($_POST["password"]);
            $userRepo = new UserRepository();
            $result = $userRepo->log_in($username);
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
                        $result = $userRepo->getUsertypeById($row->user_type);
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
                            case 3:
                                header('Location: /user/index');
                                break;
                        }
                    } else {
                        $message[] = 'Falsches Passwort!';
                        $this->message = $message;
                        http_response_code(401);
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
                        $result = $userRepo->getUsertypeById($row->user_type);
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
                        http_response_code(401);
                        $this->login();
                    }
                }

            } else {
                $message[] = 'Login fehlgeschlagen!';
                $this->message = $message;
                http_response_code(401);
                $this->login();
            }
        } else {
            $message[] = 'Login fehlgeschlagen!';
            $this->message = $message;
            http_response_code(401);
            $this->login();
        }
    }

    /** Login */
    public function login()
    {
        $view = new View('user/login');
        $view->message = $this->message;
        $view->display();
    }

    public function new_password()
    {
        Authentication::restrict_authenticated(UserTypes::$_ALL);
        $view = new View('user/password');

        $view->message = $this->message;
        $view->display();
    }

    public function edit_message()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        $nachrichtenRepo = new NachrichtRepository();
        $klassenRepo = new KlassenRepository();
        $lektionenRepo = new LektionRepository();
        $fachRepo = new FachRepository();
        if (!empty($_POST['nachrichten_id'])) {
            $lektion = null;
            $view = new View('user/edit_message');

            $nachricht = $nachrichtenRepo->readById($_POST['nachrichten_id']);
            $faecher = $fachRepo->get_faecher_by_lehrer_id($_SESSION['user']['id']);
            if ($nachricht->lektion_id != null) {
                $lektion = $lektionenRepo->readById($nachricht->lektion_id);
            }
            $view->klassen = $klassenRepo->getKlassenByLehrerID($_SESSION['user']['id']);
            $view->faecher = $faecher;
            $view->lektion = $lektion;
            $view->nachricht = $nachricht;
            $view->message = $this->message;
            $view->display();
        } else {
            $this->message = ['Keine Nachricht ausgewählt'];
            $this->new_message();
        }
    }

    public function new_message()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        $view = new View('user/new_message');

        $lektionRepo = new LektionRepository();
        $klassenRepo = new KlassenRepository();
        $fachRepo = new FachRepository();
        $dateRepo = new DatesRepository();

        $faecher = $fachRepo->get_faecher_by_lehrer_id($_SESSION['user']['id']);
        $lektion = null;
        if (isset($_POST['lektion_id']) && !empty($_POST['lektion_id'])) {
            $lektion = $lektionRepo->readById($_POST['lektion_id']);
            $view->date = $dateRepo->readById($lektion->date_id);
        }

        $view->faecher = $faecher;
        $view->lektion = $lektion;
        $view->klassen = $klassenRepo->getKlassenByLehrerID($_SESSION['user']['id']);
        $view->message = $this->message;
        $view->display();
    }

    public function update_message()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        if (!empty($_POST['edit_title']) && !empty($_POST['edit_message_text']) && !empty($_POST['nachricht_id'])) {
            $nachrichtenRepo = new NachrichtRepository();
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
            $nachrichtenRepo->update($_POST['edit_nachricht_id'], htmlspecialchars($_POST['edit_title']), htmlspecialchars($_POST['edit_message_text']), $date, $_SESSION['user']['id'], $klasse, $lektion, $fach);

            $this->message = ['Nachricht gespeichert'];
            $this->messages();
        } else {
            $this->message = ['Nachricht nicht gespeichert'];
            $this->messages();
        }
    }

    public function messages()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF_STUD);
        $view = new View('user/messages');
        $nachrichtenRepo = new NachrichtRepository();
        $klassenRepo = new KlassenRepository();
        $lektionenRepo = new LektionRepository();
        $userRepo = new UserRepository();
        $fachRepo = new FachRepository();
        $dateRepo = new DatesRepository();
        $fachIds = array();
        $view->dates = array();
        $lektionen = array();
        $nachrichten = array();
        $faecher = array();
        $klassen = array();
        /** Lehrperson */
        if ($_SESSION['userType']['id'] == 2) {
            $nachrichten = $nachrichtenRepo->get_message_by_creator_sorted($_SESSION['user']['id']);
            $klassen = $klassenRepo->getKlassenByLehrerID($_SESSION['user']['id']);
            $faecher = $fachRepo->get_faecher_by_lehrer_id($_SESSION['user']['id']);
            foreach ($faecher as $fach) {
                $fachIds[] = $fach->id;
            }
            if (!empty($fachIds)) {
                $lektionen = $lektionenRepo->get_lektionen_by_faecher($fachIds);
            }
            $dateIds = array();
            foreach ($lektionen as $lektion) {
                $dateIds[] = $lektion->date_id;
            }
            if (!empty($dateIds)) {
                $view->dates = $dateRepo->get_dates_with_ids($dateIds);
            }
        }
        /** Lernende */
        if ($_SESSION['userType']['id'] == 3) {
            $user_klassen = $klassenRepo->get_klassenID_by_student($_SESSION['user']['id']);
            $klassenIds = array();
            foreach ($user_klassen as $user_klasse) {
                $klassenIds[] = $user_klasse->klassen_id;
            }
            if (!empty($klassenIds)) {
                $faecher = $fachRepo->get_faecher_by_klassen($klassenIds);
            }

            foreach ($faecher as $fach) {
                $fachIds[] = $fach->id;
            }
            if (!empty($fachIds)) {
                $lektionen = $lektionenRepo->get_lektionen_by_faecher($fachIds);
            }
            $dateIds = array();
            foreach ($lektionen as $lektion) {
                $dateIds[] = $lektion->id;
            }
            if (!empty($klassenIds)) {
                $klassen = $klassenRepo->get_multiple_klassen_by_id($klassenIds);
            }
            $nachrichten = $nachrichtenRepo->get_message_for_student_sorted($klassenIds, $dateIds, $fachIds);
            $view->teachers = $userRepo->readAllProfs();
        }

        $view->faecher = $faecher;
        $view->lektionen = $lektionen;
        $view->nachrichten = $nachrichten;
        $view->klassen = $klassen;
        $view->message = $this->message;
        $view->display();
    }

    public function lesion_detail()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF_STUD);
        if ($_SESSION['userType']['id'] == 2) {
            $view = new View('user/edit_lesion');
        } else {
            $view = new View('user/lesion_detail');
        }
        $lektionRepo = new LektionRepository();
        $nachrichtRepo = new NachrichtRepository();
        $dateRepo = new DatesRepository();
        $userRepo = new UserRepository();
        $gebaeudeRepo = new GebaeudeRepository();
        $fachRepo = new FachRepository();

        if (isset($_GET['lesion_id']) && !empty($_GET['lesion_id'])) {
            $lesion = $lektionRepo->readById($_GET['lesion_id']);
            $date = $dateRepo->readById($lesion->date_id);
            $nachrichten = $nachrichtRepo->get_message_for_lesion_sorted($_GET['lesion_id']);
            $fach = $fachRepo->readById($lesion->fach_id);
            $user = $userRepo->readById($fach->lehrer_id);
            $zimmer = $gebaeudeRepo->get_room_by_id($lesion->zimmer);
            $stockwerk = $gebaeudeRepo->get_floor_by_id($zimmer->stockwerk_id);
            $build = $gebaeudeRepo->readById($stockwerk->gebaeude_id);
        }

        $view->fach = $fach;
        $view->zimmer = $zimmer;
        $view->stockwerk = $stockwerk;
        $view->build = $build;
        $view->user = $user;
        $view->date = $date;
        $view->nachrichten = $nachrichten;
        $view->lesion = $lesion;
        $view->message = $this->message;
        $view->display();
    }

    public function edit_lesion()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        $view = new View('user/edit_lesion');
        $lektionRepo = new LektionRepository();
        $nachrichtenRepo = new NachrichtRepository();
        $dateRepo = new DatesRepository();
        $userRepo = new UserRepository();
        $gebaeudeRepo = new GebaeudeRepository();
        $fachRepo = new FachRepository();

        if (isset($_GET['lesion_id']) && !empty($_GET['lesion_id'])) {
            $lesion = $lektionRepo->readById($_GET['lesion_id']);
            $date = $dateRepo->readById($lesion->date_id);
            $nachrichten = $nachrichtenRepo->get_message_for_lesion_sorted($_GET['lesion_id']);
            $fach = $fachRepo->readById($lesion->fach_id);
            $user = $userRepo->readById($fach->lehrer_id);
            $zimmer = $gebaeudeRepo->get_room_by_id($lesion->zimmer);
            $stockwerk = $gebaeudeRepo->get_floor_by_id($zimmer->stockwerk_id);
            $build = $gebaeudeRepo->readById($stockwerk->gebaeude_id);
        }

        $view->fach = $fach;
        $view->zimmer = $zimmer;
        $view->stockwerk = $stockwerk;
        $view->build = $build;
        $view->user = $user;
        $view->date = $date;
        $view->nachrichten = $nachrichten;
        $view->lesion = $lesion;
        $view->message = $this->message;
        $view->display();
    }

    public function update_lesion()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        $lektionRepo = new LektionRepository();
        if (isset($_POST['delete_button']) && !empty($_POST['lektion_id'])) {
            $lektionRepo->deleteById($_POST['lektion_id']);
            header('Location: /user/lesions');
        } elseif (isset($_POST['speichern_button']) && !empty($_POST['lektion_id'])) {

            $row = $lektionRepo->update(htmlspecialchars($_POST['edit_prog_them']), htmlspecialchars($_POST['edit_term_aufg']), $_POST['lektion_id']);
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
        Authentication::restrict_authenticated(UserTypes::$_PROF_STUD);
        $view = new View('user/lesions');

        $lektionenRepo = new LektionRepository();
        $fachRepo = new FachRepository();
        $dateRepo = new DatesRepository();
        $klassenRepo = new KlassenRepository();
        $buildRepo = new GebaeudeRepository();
        $lektionen = array();
        $dates = array();
        $zimmer = array();
        $klassenIds = array();
        $klassen = array();
        $faecher = array();


        if ($_SESSION['userType']['id'] == 2) {

            $faecher = $fachRepo->get_faecher_by_lehrer_id($_SESSION['user']['id']);
            $fachIds = array();
            foreach ($faecher as $fach) {
                $fachIds[] = $fach->id;
                $klassenIds[] = $fach->klassen_id;
            }
            $klassenLP = $klassenRepo->getKlassenByLehrerID($_SESSION['user']['id']);
            foreach ($klassenLP as $lp) {
                $klassenIds[] = $lp->id;
            }
            if (!empty($fachIds)) {
                $lektionen = $lektionenRepo->get_lektionen_by_faecher($fachIds);
            }
            if (!empty($klassenIds)) {
                $klassen = $klassenRepo->get_multiple_klassen_by_id($klassenIds);
            }
            $dateIds = array();
            $zimmerIds = array();
            foreach ($lektionen as $lektion) {
                $dateIds[] = $lektion->date_id;
                $zimmerIds[] = $lektion->zimmer;
            }
            if (!empty($dateIds)) {
                $dates = $dateRepo->get_dates_with_ids($dateIds);
            }
            if (!empty($zimmerIds)) {
                $zimmer = $buildRepo->get_rooms_by_ids($zimmerIds);
            }

        } elseif ($_SESSION['userType']['id'] == 3) {

            $userRepo = new UserRepository();
            $user_klassen = $klassenRepo->get_klassenID_by_student($_SESSION['user']['id']);
            foreach ($user_klassen as $user_klasse) {
                $klassenIds[] = $user_klasse->klassen_id;
            }
            if (!empty($klassenIds)) {
                $klassen = $klassenRepo->get_multiple_klassen_by_id($klassenIds);
                $faecher = $fachRepo->get_faecher_by_klassen($klassenIds);
            }
            $fachIds = array();
            foreach ($faecher as $fach) {
                $fachIds[] = $fach->id;
            }
            if (!empty($fachIds)) {
                $lektionen = $lektionenRepo->get_lektionen_by_faecher($fachIds);
            }
            $dateIds = array();
            $zimmerIds = array();
            foreach ($lektionen as $lektion) {
                $dateIds[] = $lektion->date_id;
                $zimmerIds[] = $lektion->zimmer;
            }
            if (!empty($dateIds)) {
                $dates = $dateRepo->get_dates_with_ids_asc($dateIds);
            }
            if (!empty($zimmerIds)) {
                $zimmer = $buildRepo->get_rooms_by_ids($zimmerIds);
            }
            $view->profs = $userRepo->readAllProfs();
        }

        $view->zimmer = $zimmer;
        $view->dates = $dates;
        $view->lektionen = $lektionen;
        $view->klassen = $klassen;
        $view->faecher = $faecher;
        $view->message = $this->message;
        $view->display();
    }

    public function edit_klasse()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        if (!empty($_POST['klassen_id'])) {

            $lernende_out = array();
            $lernende_in = array();

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

            $view = new View('user/edit_klasse');

            $userRepo = new UserRepository();

            $klasse = $klassenRepo->readById($_POST['klassen_id']);
            $klassen_users = $klassenRepo->get_user_ids_of_klasse($_POST['klassen_id']);

            $ids = array();
            foreach ($klassen_users as $klassen_user) {
                $ids[] = $klassen_user->user_id;
            }
            if (!empty($ids)) {
                $lernende_in = $userRepo->get_multiple_user_by_id($ids);
            }

            $lernendeInIds = array();
            foreach ($lernende_in as $lern) {
                $lernendeInIds[] = $lern->id;
            }
            if (!empty($lernendeInIds)) {
                $lernende_out = $userRepo->readAllStudsExceptIds($lernendeInIds);
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

    public function edit_fach()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        $view = new View('user/fach');

        $fachRepo = new FachRepository();
        $userRepo = new UserRepository();
        $klassenRepo = new KlassenRepository();

        if (!empty($_POST['fach_id'])) {
            $view->fach = $fachRepo->readById($_POST['fach_id']);
            $view->lehrer = $userRepo->readAllProfs();
            $view->klassen = $klassenRepo->readAll();
        }

        $view->message = $this->message;
        $view->display();
    }

    public function update_fach()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        $fachRepo = new FachRepository();
        if (!empty($_POST['edit_fachtitle']) && !empty($_POST['edit_fach_lp_select']) && !empty($_POST['klassen_select'])) {
            $fachRepo->updateFach(htmlspecialchars($_POST['edit_fachtitle']), $_POST['klassen_select'], $_POST['edit_fach_lp_select'], $_POST['fach_id']);
            $this->message = ['Fach gespeichert'];
            $this->klassen();
        } else {
            $this->message = ['Bitte alle Felder füllen'];
            $this->klassen();
        }
    }

    public function lehrer()
    {
        Authentication::restrict_authenticated(UserTypes::$_STUD);
        $view = new View('user/lehrer');
        $userRepo = new UserRepository();
        $fachRepo = new FachRepository();
        $klassenRepo = new KlassenRepository();

        $klassen = array();
        $faecher = array();

        $user_klassen = $klassenRepo->get_klassenID_by_student($_SESSION['user']['id']);
        $klassenIds = array();
        foreach ($user_klassen as $user_klasse) {
            $klassenIds[] = $user_klasse->klassen_id;
        }

        if (!empty($klassenIds)) {
            $klassen = $klassenRepo->get_multiple_klassen_by_id($klassenIds);
            $faecher = $fachRepo->get_faecher_by_klassen($klassenIds);
        }

        $view->klassen = $klassen;
        $view->faecher = $faecher;
        $view->users = $userRepo->readAllProfs();

        $view->message = $this->message;

        $view->display();
    }

    public function stud_klassen()
    {
        Authentication::restrict_authenticated(UserTypes::$_STUD);
        $view = new View('user/stud_klassen');

        $user = array();
        $klassen = array();
        $klassen_users = array();

        $klassenRepo = new KlassenRepository();
        $userRepo = new UserRepository();

        $user_klassen = $klassenRepo->get_klassenID_by_student($_SESSION['user']['id']);
        $klassenIds = array();
        foreach ($user_klassen as $user_klasse) {
            $klassenIds[] = $user_klasse->klassen_id;
        }
        if (!empty($klassenIds)) {
            $klassen = $klassenRepo->get_multiple_klassen_by_id($klassenIds);
            $klassen_users = $klassenRepo->get_user_ids_of_multiple_klasse($klassenIds);
        }
        $userIds = array();
        foreach ($klassen_users as $klassen_user) {
            $userIds[] = $klassen_user->user_id;
        }
        if (!empty($userIds)) {
            $user = $userRepo->get_multiple_user_by_id($userIds);
        }

        $view->profs = $userRepo->readAllProfs();
        $view->users = $user;
        $view->klassenUser = $klassen_users;
        $view->klassen = $klassen;
        $view->message = $this->message;
        $view->display();
    }

    /** Functions */
    public function check_changePassword()
    {
        Authentication::restrict_authenticated(UserTypes::$_ALL);
        if (isset($_POST["old_password"]) && !empty($_POST["old_password"]) && isset($_POST["new_password"]) && !empty($_POST["new_password"])) {
            $old_password = htmlspecialchars($_POST["old_password"]);
            $new_password = htmlspecialchars($_POST["new_password"]);
            $password_pattern = "#(?=^.{8,}$)^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$#";
            if (preg_match($password_pattern, $new_password)) {
                $userRepo = new UserRepository();


                $result = $userRepo->readById($_SESSION['user']['id']);

                if ($result->initial_pw == 0) {
                    $affectedRows = $userRepo->change_password($new_password, $_SESSION['user']['id']);
                } else {
                    $affectedRows = $userRepo->update_password($new_password, $old_password, $_SESSION['user']['id']);
                }

                if ($affectedRows == 1) {
                    $message[] = 'Passwort geändert!';
                    $this->message = $message;
                    switch ($_SESSION['userType']['id']) {
                        case 1:
                            header('Location: admin/index');
                            break;
                        case 2:
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
        Authentication::restrict_authenticated(UserTypes::$_PROF_STUD);
        $view = new View('user/index');

        $lesionRepo = new LektionRepository();
        $klassenRepo = new KlassenRepository();
        $fachRepo = new FachRepository();
        $messageRepo = new NachrichtRepository();
        $userRepo = new UserRepository();
        $dateIds = array();
        $fachIds = array();
        $klassenIds = array();
        $lektionIds = array();
        $nachrichten = array();
        $faecher = array();
        $daten = array();
        $lesions = array();
        $klassen = array();

        switch ($_SESSION['userType']['id']) {
            case 2:
                $faecher = $fachRepo->get_faecher_by_lehrer_id($_SESSION['user']['id']);
                $klassen = $klassenRepo->getKlassenByLehrerID($_SESSION['user']['id']);
                foreach ($faecher as $fach) {
                    $fachIds[] = $fach->id;
                }
                if (!empty($fachIds)) {
                    $lesions = $lesionRepo->get_lektionen_by_faecher($fachIds);
                }
                foreach ($lesions as $lesion) {
                    $dateIds[] = $lesion->date_id;
                }
                if (!empty($dateIds)) {
                    $daten = $lesionRepo->get_date_by_lektionen($dateIds);
                }
                $nachrichten = $messageRepo->get_message_by_creator_sorted($_SESSION['user']['id'], 6);
                break;
            case 3:
                $userKlassen = $klassenRepo->get_klassenID_by_student($_SESSION['user']['id']);
                foreach ($userKlassen as $userKlasse) {
                    $klassenIds[] = $userKlasse->klassen_id;
                }
                if (!empty($klassenIds)) {
                    $faecher = $fachRepo->get_faecher_by_klassen($klassenIds);
                }
                foreach ($faecher as $fach) {
                    $fachIds[] = $fach->id;
                }
                if (!empty($fachIds)) {
                    $lesions = $lesionRepo->get_lektionen_by_faecher($fachIds);
                }
                foreach ($lesions as $lesion) {
                    $dateIds[] = $lesion->date_id;
                    $lektionIds[] = $lesion->id;
                }
                if (!empty($klassenIds)) {
                    $klassen = $klassenRepo->get_multiple_klassen_by_id($klassenIds);
                }
                if (!empty($dateIds)) {
                    $daten = $lesionRepo->get_date_by_lektionen($dateIds);
                }
                $nachrichten = $messageRepo->get_message_for_student_sorted($klassenIds, $lektionIds, $fachIds, 6);
                break;
            default:
                $nachrichten = array();
                $faecher = array();
                $daten = array();
                $lesions = array();
                $klassen = array();
        }

        $view->klassen = $klassen;
        $view->profs = $userRepo->readAllProfs();
        $view->nachrichten = $nachrichten;
        $view->faecher = $faecher;
        $view->daten = $daten;
        $view->lektionen = $lesions;
        $view->message = $this->message;
        $view->display();
    }

    public function edit_profile()
    {
        Authentication::restrict_authenticated(UserTypes::$_ALL);
        $view = new View('user/profile');
        $view->message = $this->message;
        $view->display();

    }

    public function delete_selected_klassen()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        if (!empty($_POST['klassen'])) {
            $klassenRepo = new KlassenRepository();

            foreach ($_POST['klassen'] as $klasse) {
                $klassenRepo->deleteById($klasse);
            }

            $message[] = 'Klassen gelöscht';
            $this->message = $message;
            $this->klassen();
        }
    }

    public function klassen()
    {
        Authentication::restrict_authenticated(UserTypes::$_ALL);
        $klassenRepo = new KlassenRepository();
        $fachRepo = new FachRepository();
        $userRepo = new UserRepository();
        $view = new View('user/klassen');

        $faecher = array();
        $klassen = array();

        if ($_SESSION['userType']['id'] == 2) {
            $klassenLp = $klassenRepo->getKlassenByLehrerID($_SESSION['user']['id']);
            $faecher = $fachRepo->get_faecher_by_lehrer_id($_SESSION['user']['id']);
            $klassenIds = array();
            foreach ($klassenLp as $klasse) {
                $klassenIds[] = $klasse->id;
            }
            foreach ($faecher as $fach) {
                $klassenIds[] = $fach->klassen_id;
            }
            if (!empty($klassenIds)) {
                $klassen = $klassenRepo->get_multiple_klassen_by_id($klassenIds);
            }
        }

        $view->allKlassen = $klassenRepo->readAll();
        $view->lehrer = $userRepo->readAllProfs();
        $view->klassen = $klassen;
        $view->faecher = $faecher;
        $view->allFaecher = $fachRepo->readAll();
        $view->message = $this->message;
        $view->display();
    }

    public function create_klasse()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        $klassenRepo = new KlassenRepository();
        if (!empty($_POST['new_klassenname'])) {
            try {
                $klassenRepo->createKlasse(htmlspecialchars($_POST['new_klassenname']), $_POST['klassen_lp_select']);
                $this->message = ['Klasse erstellt'];
                unset($_POST);
                $this->klassen();
            } catch (Exception $e) {
                $message[] = "Die Klasse konnte nicht erstellt werden!";
                $this->message = $message;
                $this->klassen();
            }
        } else {
            $message[] = "Bitte alle Felder ausfüllen!";
            $this->message = $message;
            $this->klassen();
        }
    }

    public function create_fach()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        $fachRepo = new FachRepository();
        if (!empty($_POST['new_fachtitle']) && !empty($_POST['new_fach_lp_select']) && !empty($_POST['klassen_select'])) {
            try {
                $fachRepo->create_new_fach(htmlspecialchars($_POST['new_fachtitle']), $_POST['klassen_select'], $_POST['new_fach_lp_select']);
                $message = ['Fach erstellt'];
                unset($_POST);
            } catch (Exception $e) {
                $message[] = "Das Fach konnte nicht erstellt werden!";
            }
        } else {
            $message[] = "Bitte alles ausfüllen!";
        }

        $this->message = $message;
        $this->klassen();
    }

    public function delete_selected_faecher()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        $fachRepo = new FachRepository();
        if (isset($_POST['faecher']) && !empty($_POST['faecher']) && $_SESSION['userType']['id'] == 2) {
            foreach ($_POST['faecher'] as $fach) {
                $fachRepo->deleteById($fach);
            }
        }
        $this->message = ['Fächer gelöscht'];
        $this->klassen();
    }

    public function delete_selected_lesions()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        $lektionRepo = new LektionRepository();
        $dateRepo = new DatesRepository();

        if (isset($_POST['lesions']) && !empty($_POST['lesions']) && $_SESSION['userType']['id'] == 2) {
            foreach ($_POST['lesions'] as $lektion) {
                $lesion = $lektionRepo->readById($lektion);
                $lektionRepo->deleteById($lektion);
                $dateRepo->deleteById($lesion->date_id);
            }
        }
        $this->message = ['Lektionen gelöscht'];
        $this->lesions();
    }

    public function create_message()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        if (!empty($_POST['new_title']) && !empty($_POST['new_message_text'])) {
            $nachrichtenRepo = new NachrichtRepository();
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

            $nachrichtenRepo->create(htmlspecialchars($_POST['new_title']), htmlspecialchars($_POST['new_message_text']), $date, $_SESSION['user']['id'], $klasse, $lektion, $fach);

            $this->message = ['Nachricht erstellt'];
            $this->messages();
        }
    }

    public function delete_selected_message()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        $nachrichtenRepo = new NachrichtRepository();
        if (isset($_POST['messages']) && !empty($_POST['messages']) && $_SESSION['userType']['id'] == 2) {
            foreach ($_POST['messages'] as $message) {
                $nachrichtenRepo->deleteById($message);
            }
        }
        $this->message = ['Nachricht gelöscht'];
        $this->messages();
    }

    public function check_upload()
    {
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        if (!empty($_POST['start_time']) && !empty($_POST['end_time']) && !empty($_POST['zimmer_select']) && !empty($_POST['fach_select'])) {
            $uploadDir = 'data/uploads/';
            $uploadFile = $uploadDir . basename($_FILES['userfile']['name']);
            $lektionRepo = new LektionRepository();
            $fachRepo = new FachRepository();

            $fach = $fachRepo->readById($_POST['fach_select']);

            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile)) {
                $row = 0;
                if (($handle = fopen($uploadFile, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                        if ($row > 0) {
                            $date = strtotime($data[1]);
                            $date = date('Y-m-d', $date);
                            $startZeit = htmlspecialchars($_POST['start_time']) . ':00';
                            $endZeit = htmlspecialchars($_POST['end_time']) . ':00';

                            $dateID = $lektionRepo->create_new_date($date, $date, $startZeit, $endZeit, 0);
                            if (!empty($dateID) && !empty($fach)) {
                                $lektionRepo->create_new_lesion($data[2], $data[3], $dateID, $_POST['zimmer_select'], $fach->id);
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
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        $view = new View('user/upload');

        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && $_SESSION['userType']['id'] == 2) {
            $gebaeudeRepo = new GebaeudeRepository();
            $fachRepo = new FachRepository();

            $view->zimmerList = $gebaeudeRepo->readAllRooms();
            $view->stockwerke = $gebaeudeRepo->readAllFloors();
            $view->buildings = $gebaeudeRepo->readAll();
            $view->faecher = $fachRepo->get_faecher_by_lehrer_id($_SESSION['user']['id']);
            $view->message = $this->message;
        } else {
            header('Location: /user/login');
        }

        $view->display();
    }

}