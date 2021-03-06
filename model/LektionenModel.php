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

    public function get_lektionen_by_klassen_ids($klassenIDs) {
        $inKlassen = rtrim(str_repeat('?,', count($klassenIDs)), ',');
        $query = "SELECT * FROM $this->tableName WHERE klassen_id IN ($inKlassen)";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $this->DynamicBindVariables($statement, $klassenIDs, null);
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

    public function get_date_by_lektionen($lektionDateIDs) {
        $inLektionen = rtrim(str_repeat('?,', count($lektionDateIDs)), ',');
        $query = "SELECT * FROM $this->datesTable WHERE id IN ($inLektionen)";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement = $this->DynamicBindVariables($statement, $lektionDateIDs, null);
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

    public function get_lektionen_by_faecher($faecherIds) {
        $inFaecher = rtrim(str_repeat('?,', count($faecherIds)), ',');
        $query = "SELECT * FROM $this->tableName WHERE fach_id IN ($inFaecher)";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement = $this->DynamicBindVariables($statement, $faecherIds);
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

    public function get_lektionen_by_fach_id($fachID)
    {
        $query = "SELECT * FROM $this->tableName WHERE fach_id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $fachID);
        $statement->execute();

        $result = $statement->get_result();
        if (!$result) {
            throw new Exception($statement->error);
        }

        $row = $result->fetch_object();

        $result->close();

        return $row;
    }

    public function update($progThem, $termAufg, $lektionID) {
        $query = "UPDATE $this->tableName SET programm_themen = ?, termine_aufgaben = ? where id = ?";
        $connection = ConnectionHandler::getConnection();
        $statement = $connection->prepare($query);
        $statement->bind_param('ssi', $progThem, $termAufg, $lektionID);
        $statement->execute();
        return $connection->affected_rows;
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

    public function create_new_lesion($progThem, $termAufg, $dateID, $zimmerID, $fachID) {
        $query = "INSERT INTO $this->tableName (programm_themen, termine_aufgaben, date_id, zimmer, fach_id) VALUES (?, ?, ?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ssiii', $progThem, $termAufg, $dateID, $zimmerID, $fachID);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }

    function DynamicBindVariables($stmt, $params, $params2 = null)
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

            // Call the Function bind_param with dynamic Parameters
            call_user_func_array(array($stmt,'bind_param'), $bind_names);
        }
        return $stmt;
    }
}