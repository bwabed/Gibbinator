<?php

require_once('model/DatesModel.php');
require_once('model/LektionenModel.php');
require_once('model/KlassenModel.php');
require_once('model/GebaeudeModel.php');

/**
 * Created by PhpStorm.
 * User: dimit
 * Date: 12.01.2019
 * Time: 12:09
 */
class ProfController
{
    private $message;

    public function __construct()
    {
        $view = new View('header', array('title' => 'Benutzer', 'heading' => 'Benutzer'));
        $view->display();
    }

    public function index()
    {
        $view = new View('user_index');
        $view->display();
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
                }
            }
        }
    }

    public function __destruct()
    {
        $view = new View('footer');
        $view->message = $this->message;
        $view->display();
    }
}