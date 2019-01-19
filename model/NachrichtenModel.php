<?php

require_once('lib/model.php');

/**
 * Created by PhpStorm.
 * User: dimit
 * Date: 04.01.2019
 * Time: 18:42
 */

class NachrichtenModel extends Model
{
    protected $tableName = 'nachrichten';

    public function get_message_by_creator($creatorID) {
        $query = "SELECT * FROM $this->tableName WHERE erfasser_id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $creatorID);
        $statement->execute();
        $result = $statement->get_result();
        if (!$result) {
            throw new Exception($statement->error);
        }

        // Datensätze aus dem Resultat holen und in das Array $rows speichern
        $rows = array();
        while ($row = $result->fetch_object()) {
            $rows[] = $row;
        }

        return $rows;
    }
}