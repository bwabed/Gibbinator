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

    /** Start */
    public function __construct()
    {
        $view = new View('header', array('title' => 'Benutzer', 'heading' => 'Benutzer'));
        $view->display();
    }

    public function index()
    {
        header('Location: /user/index');
    }

    /** Views */


    /** Functions */


    /** End */
    public function __destruct()
    {
        $view = new View('footer');
        $view->message = $this->message;
        $view->display();
    }
}