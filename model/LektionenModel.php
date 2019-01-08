<?php

require_once 'lib/Model.php';

/**
 * Created by PhpStorm.
 * User: dimit
 * Date: 04.01.2019
 * Time: 18:36
 */

class LektionenModel extends Model
{
    protected $tableName = 'lektionen';

    public function get_lektionen_by_klasse($klassenId) {
        $query = "SELECT * FROM $this->tableName WHERE klassen_ID = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $klassenId);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    public function get_lektionen_by_klassen_lp($lehrperson)
    {
        $query = "SELECT * FROM $this->tableName WHERE lehrer_ID = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $lehrperson);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }
}