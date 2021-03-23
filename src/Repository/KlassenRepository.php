<?php


namespace App\Repository;


use App\Database\ConnectionHandler;
use Exception;

class KlassenRepository extends Repository
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

    public function updateKlasse($klassenID, $klassenName, $klassenLp)
    {
        $query = "UPDATE $this->tableName SET `name` = ?, klassen_lp = ? where id = ?";
        $connection = ConnectionHandler::getConnection();
        $statement = $connection->prepare($query);
        $statement->bind_param('sii', $klassenName, $klassenLp, $klassenID);
        $statement->execute();
        return $connection->affected_rows;
    }

    public function get_klassenID_by_student($studID)
    {
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

    public function get_multiple_klassen_by_id($klassenIDs)
    {
        $inKlassen = rtrim(str_repeat('?,', count($klassenIDs)), ',');
        $query = "SELECT * FROM $this->tableName WHERE id IN ($inKlassen)";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement = $this->dynamic_bind_variables($statement, $klassenIDs);
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

    public function delete_user_klasse_by_user($userID)
    {
        $query = "DELETE FROM $this->userKlassenTable WHERE user_id = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $userID);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    public function get_user_ids_of_klasse($klassenID)
    {
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

    public function get_user_ids_of_multiple_klasse($klassenIDs)
    {
        $inKlassen = rtrim(str_repeat('?,', count($klassenIDs)), ',');
        $query = "SELECT * FROM $this->userKlassenTable WHERE klassen_id IN ($inKlassen)";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement = $this->dynamic_bind_variables($statement, $klassenIDs);
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

    private function dynamic_bind_variables($stmt, $params, $params2 = null, $params3 = null)
    {
        if ($params != null) {
            // Generate the Type String (eg: 'issisd')
            $types = '';
            foreach ($params as $param) {
                if (is_int($param)) {
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
                foreach ($params2 as $param2) {
                    if (is_int($param2)) {
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
                foreach ($params3 as $param3) {
                    if (is_int($param3)) {
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
            for ($i; $i < count($params); $i++) {
                // Create a variable Name
                $bind_name = 'bind' . $i;
                // Add the Parameter to the variable Variable
                $$bind_name = $params[$i];
                // Associate the Variable as an Element in the Array
                $bind_names[] = &$$bind_name;
            }

            $j = 0;

            if ($params2 != null) {
                for ($j; $j < count($params2); $j++) {
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
                for ($k; $k < count($params3); $k++) {
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
            call_user_func_array(array($stmt, 'bind_param'), $bind_names);
        }
        return $stmt;
    }
}