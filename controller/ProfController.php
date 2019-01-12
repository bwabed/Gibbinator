<?php

require_once('model/DatesModel.php');

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
        $view = new View('prof_header', array('title' => 'Benutzer', 'heading' => 'Benutzer'));
        $view->display();
    }

    public function index() {
        $view = new View('user_index');
        $view->display();
    }

    public function __destruct()
    {
        $view = new View('footer');
        $view->message = $this->message;
        $view->display();
    }
}