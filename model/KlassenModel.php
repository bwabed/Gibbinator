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

    public function getKlassenByLehrerID($lehrerID)
    {
        $query = "SELECT * FROM $this->tableName WHERE klassen_lp = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $lehrerID);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    public function createKlasse($klassenName, $klassenLp, $abteilungsId)
    {

        $query = "INSERT INTO $this->tableName (`name`, klassen_lp, abteilungs_id) VALUES (?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('sii', $klassenName, $klassenLp, $abteilungsId);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;

    }

    public function updateKlasse($klassenID, $klassenName, $klassenLp, $abteilungs_id) {
        $query = "UPDATE $this->tableName SET `name` = ?, klassen_lp = ?, abteilungs_id = ? where id = ?";
        $connection = ConnectionHandler::getConnection();
        $statement = $connection->prepare($query);
        $statement->bind_param('siii', $new_email, $userID, $old_email);
        $statement->execute();
        return $connection->affected_rows;
    }
}