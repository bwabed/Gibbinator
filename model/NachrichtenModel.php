<?php

require_once('lib/Model.php');

/**
 * Created by PhpStorm.
 * User: dimit
 * Date: 04.01.2019
 * Time: 18:42
 */

class NachrichtenModel extends Model
{
    protected $tableName = 'nachricht';

    public function get_message_by_creator_sorted($creatorID, $max = 100) {
        $query = "SELECT * FROM $this->tableName WHERE erfasser_id = ? ORDER BY DATE(erstellt_am) DESC  LIMIT 0, $max";
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

    public function create($titel, $text, $erstelltAm, $erstellt_von, $klasse, $lektion, $fach)
    {
        $query = "INSERT INTO $this->tableName (titel, text, erstellt_am, erfasser_id, klassen_id, lektion_id, fach_id) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('sssiiii', $titel, $text, $erstelltAm, $erstellt_von, $klasse, $lektion, $fach);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }

    public function update($nachrichtID, $title, $text, $erstellt_am, $erfasserId, $klasse, $lektion, $fach) {
        $query = "UPDATE $this->tableName SET titel = ?, text = ?, erstellt_am = ?, erfasser_id = ?, klassen_id = ?, lektion_id = ?, fach_id = ? where id = ?";
        $connection = ConnectionHandler::getConnection();
        $statement = $connection->prepare($query);
        $statement->bind_param('sssiiiii', $title, $text, $erstellt_am, $erfasserId, $klasse, $lektion, $fach, $nachrichtID);
        $statement->execute();
        return $connection->affected_rows;
    }

    public function get_message_for_student_sorted($klassenIDs, $lektionIDs, $fachIds, $max = 100) {
        $inKlasse = rtrim(str_repeat('?,', count($klassenIDs)), ',');
        $inLektion = rtrim(str_repeat('?,', count($lektionIDs)), ',');
        $inFach = rtrim(str_repeat('?,', count($fachIds)), ',');
        $query = "SELECT * FROM $this->tableName WHERE (klassen_id IN ($inKlasse) OR lektion_id IN ($inLektion) OR fach_id IN ($inFach)) OR (fach_id = null AND klassen_id = null AND lektion_id = null) ORDER BY DATE(erstellt_am) DESC LIMIT 0, $max";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement = $this->DynamicBindVariables($statement, $klassenIDs, $lektionIDs, $fachIds);
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

    public function get_message_for_prof_sorted($creatorID, $klassenIDs, $lektionIDs) {
        $inKlasse = rtrim(str_repeat('?,', count($klassenIDs)), ',');
        $inLektion = rtrim(str_repeat('?,', count($lektionIDs)), ',');
        $query = "SELECT * FROM $this->tableName WHERE ersteller_id = ? OR klassen_id IN ($inKlasse) OR lektion_id IN ($inLektion) ORDER BY DATE(erstellt_am) DESC";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $creatorID);
        $statement = $this->DynamicBindVariables($statement, $klassenIDs, $lektionIDs);
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

    public function get_message_for_lesion_sorted($lesionID) {
        $query = "SELECT * FROM $this->tableName WHERE lektion_id = ? ORDER BY DATE(erstellt_am) DESC";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $lesionID);
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

    private function DynamicBindVariables($stmt, $params, $params2 = null, $params3 = null)
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

            if ($params2 != null) {
                for ($j=0; $j<count($params2);$j++)
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

            if ($params3 != null) {
                for ($k=0; $k<count($params3);$k++)
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