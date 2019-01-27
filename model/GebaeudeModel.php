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

    public function get_rooms_by_ids($roomIds) {
        $inRooms = rtrim(str_repeat('?,', count($roomIds)), ',');
        $query = "SELECT * FROM $this->roomTable WHERE id IN ($inRooms)";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $this->DynamicBindVariables($statement, $roomIds, null, null);
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

    public function addRoom($name, $stockID, $optText) {
        $query = "INSERT INTO $this->roomTable (stockwerk_id, bezeichnung, optional_text) VALUES (?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('iss', $stockID, $name, $optText);

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

    public function deleteRoomByStockwerkId($stocckwerkID)
    {
        $query = "DELETE FROM $this->roomTable WHERE stockwerk_id = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $stocckwerkID);

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

    private function DynamicBindVariables($stmt, $params, $params2, $params3)
    {
        if ($params != null)
        {
            // Generate the Type String (eg: 'issisd')
            $types = '';
            foreach($params as $param)
            {
                if(is_int($param)) {
                    // Integer
                    $types .= 'i';
                } elseif (is_float($param)) {
                    // Double
                    $types .= 'd';
                } elseif (is_string($param)) {
                    // String
                    $types .= 's';
                } else {
                    // Blob and Unknown
                    $types .= 'b';
                }
            }

            if ($params2 != null) {
                foreach($params2 as $param2)
                {
                    if(is_int($param2)) {
                        // Integer
                        $types .= 'i';
                    } elseif (is_float($param2)) {
                        // Double
                        $types .= 'd';
                    } elseif (is_string($param2)) {
                        // String
                        $types .= 's';
                    } else {
                        // Blob and Unknown
                        $types .= 'b';
                    }
                }
            }

            if ($params3 != null) {
                foreach($params3 as $param3)
                {
                    if(is_int($param3)) {
                        // Integer
                        $types .= 'i';
                    } elseif (is_float($param3)) {
                        // Double
                        $types .= 'd';
                    } elseif (is_string($param3)) {
                        // String
                        $types .= 's';
                    } else {
                        // Blob and Unknown
                        $types .= 'b';
                    }
                }
            }

            // Add the Type String as the first Parameter
            $bind_names[] = $types;

            $i = 0;

            // Loop thru the given Parameters
            for ($i; $i<count($params);$i++)
            {
                // Create a variable Name
                $bind_name = 'bind' . $i;
                // Add the Parameter to the variable Variable
                $$bind_name = $params[$i];
                // Associate the Variable as an Element in the Array
                $bind_names[] = &$$bind_name;
            }

            $j = 0;

            if ($params2 != null) {
                for ($j; $j<count($params2);$j++)
                {
                    $number = $i + $j;
                    // Create a variable Name
                    $bind_name = 'bind' . $number;
                    // Add the Parameter to the variable Variable
                    $$bind_name = $params2[$j];
                    // Associate the Variable as an Element in the Array
                    $bind_names[] = &$$bind_name;
                }
            }

            $k = 0;

            if ($params3 != null) {
                for ($k; $k<count($params3);$k++)
                {
                    $number = $i + $j + $k;
                    // Create a variable Name
                    $bind_name = 'bind' . $number;
                    // Add the Parameter to the variable Variable
                    $$bind_name = $params3[$k];
                    // Associate the Variable as an Element in the Array
                    $bind_names[] = &$$bind_name;
                }
            }

            // Call the Function bind_param with dynamic Parameters
            call_user_func_array(array($stmt,'bind_param'), $bind_names);
        }
        return $stmt;
    }
}