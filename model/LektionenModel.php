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
    protected $tableName = 'lektion';
    protected $datesTable = 'dates';

    public function get_lektionen_by_klasse($klassenId) {
        $query = "SELECT * FROM $this->tableName WHERE klassen_id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $klassenId);
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

    public function get_lektionen_by_lp($lehrpersonId)
    {
        $query = "SELECT * FROM $this->tableName WHERE lehrer_id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $lehrpersonId);
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

    public function create_new_date($startDate, $endDate, $startTime, $endTime, $allDay) {
        $query = "INSERT INTO $this->datesTable (start_date, end_date, start_time, end_time, all_day) VALUES (?, ?, ?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ssssi', $startDate, $endDate, $startTime, $endTime, $allDay);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }

    public function create_new_lesion($klassenID, $lehrerID, $titel, $progThem, $termAufg, $dateID, $zimmerID) {
        $query = "INSERT INTO $this->tableName (klassen_id, lehrer_id, titel, programm_themen, termine_aufgaben, date_id, zimmer) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('iisssii', $klassenID, $lehrerID, $titel, $progThem, $termAufg, $dateID, $zimmerID);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }
}