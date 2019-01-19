<?php

require_once('lib/Model.php');

/**
 * Created by PhpStorm.
 * User: dimi
 * Date: 2019-01-18
 * Time: 13:02
 */
class KlassenModel extends Model
{
    protected $tableName = 'klasse';
    protected $userKlassenTable = 'user_klasse';

    public function getKlassenByLehrerID($lehrerID)
    {
        $query = "SELECT * FROM $this->tableName WHERE klassen_lp = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $lehrerID);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    public function createKlasse($klassenName, $klassenLp)
    {

        $query = "INSERT INTO $this->tableName (`name`, klassen_lp) VALUES (?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('si', $klassenName, $klassenLp);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;

    }

    public function updateKlasse($klassenID, $klassenName, $klassenLp) {
        $query = "UPDATE $this->tableName SET `name` = ?, klassen_lp = ? where id = ?";
        $connection = ConnectionHandler::getConnection();
        $statement = $connection->prepare($query);
        $statement->bind_param('sii', $klassenName, $klassenLp, $klassenID);
        $statement->execute();
        return $connection->affected_rows;
    }

    public function get_klasse_by_student($studID) {
        $query = "SELECT * FROM $this->userKlassenTable WHERE user_id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $studID);
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

    public function create_user_klasse($klassenID, $userID)
    {

        $query = "INSERT INTO $this->userKlassenTable (user_id, klassen_id) VALUES (?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ii', $userID, $klassenID);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;

    }

    public function delete_user_klasse_by_user($userID) {
        $query = "DELETE FROM $this->userKlassenTable WHERE user_id=?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $userID);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function get_user_ids_of_klasse($klassenID) {
        $query = "SELECT * FROM $this->userKlassenTable WHERE klassen_id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $klassenID);
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