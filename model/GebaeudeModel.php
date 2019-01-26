<?php

require_once 'lib/Model.php';
/**
 * Created by PhpStorm.
 * User: dimit
 * Date: 15.01.2019
 * Time: 11:46
 */

class GebaeudeModel extends Model
{
    protected $tableName = 'gebaeude';
    protected $floorTable = 'stockwerk';
    protected $roomTable = 'zimmer';
    protected $connectionTable = 'stockwerk_zimmer';

    public function addBuilding($name, $street, $number, $plz, $ort) {
        $query = "INSERT INTO $this->tableName (bezeichnung, strasse, nr, plz, ort) VALUES (?, ?, ?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('sssis', $name, $street, $number, $plz, $ort);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }

    public function addStockwerk($name, $gebaeudeID, $floorNumber) {
        $query = "INSERT INTO $this->floorTable (gebaeude_ID, bezeichnung, nummer) VALUES (?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('isi', $gebaeudeID, $name, $floorNumber);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }

    public function deleteFloorById($floorID)
    {
        $query = "DELETE FROM {$this->floorTable} WHERE id=?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $floorID);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function deleteConnectionById($connectionID)
    {
        $query = "DELETE FROM {$this->connectionTable} WHERE id=?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $connectionID);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function get_floors_by_gebaeude_id($gebaeudeID) {
        $query = "SELECT * FROM $this->floorTable WHERE gebaeude_id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $gebaeudeID);
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

    public function get_connections_by_floor_id($floorID) {
        $query = "SELECT * FROM $this->connectionTable WHERE stockwerk_id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $floorID);
        $statement->execute();
        $result = $statement->get_result();
        if (!$result) {
            throw new Exception($statement->error);
        }

        $rows = array();
        while ($row = $result->fetch_object()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function get_connection_by_room_id($roomID) {
        $query = "SELECT * FROM $this->connectionTable WHERE zimmer_id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $roomID);
        $statement->execute();
        $result = $statement->get_result();
        if (!$result) {
            throw new Exception($statement->error);
        }

        $row = $result->fetch_object();

        $result->close();

        return $row;
    }

    public function get_floor_by_id($floorID) {
        $query = "SELECT * FROM $this->floorTable WHERE id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $floorID);
        $statement->execute();
        $result = $statement->get_result();
        if (!$result) {
            throw new Exception($statement->error);
        }

        $row = $result->fetch_object();

        $result->close();

        return $row;
    }

    public function get_room_by_id($roomID) {
        $query = "SELECT * FROM $this->roomTable WHERE id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $roomID);
        $statement->execute();
        $result = $statement->get_result();
        if (!$result) {
            throw new Exception($statement->error);
        }

        $row = $result->fetch_object();

        $result->close();

        return $row;
    }

    public function addRoom($name, $optText) {
        $query = "INSERT INTO $this->roomTable (bezeichnung, optional_text) VALUES (?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ss', $name, $optText);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }

    public function deleteRoomById($roomID)
    {
        $query = "DELETE FROM $this->roomTable WHERE id=?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $roomID);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function addConnectionRoomFloor($roomID, $floorID) {
        $query = "INSERT INTO $this->connectionTable (stockwerk_id, zimmer_id) VALUES (?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ii', $floorID, $roomID);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }

    public function readAllFloors($max = 100)
    {
        $query = "SELECT * FROM $this->floorTable LIMIT 0, $max";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->execute();

        $result = $statement->get_result();
        if (!$result) {
            throw new Exception($statement->error);
        }

        $rows = array();
        while ($row = $result->fetch_object()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function readAllRooms($max = 100)
    {
        $query = "SELECT * FROM {$this->roomTable} LIMIT 0, $max";

        $statement = ConnectionHandler::getConnection()->prepare($query);
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

    public function readAllConnections($max = 100)
    {
        $query = "SELECT * FROM {$this->connectionTable} LIMIT 0, $max";

        $statement = ConnectionHandler::getConnection()->prepare($query);
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