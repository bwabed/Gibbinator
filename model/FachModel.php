<?php
require_once('lib/Model.php');

/**
 * Created by PhpStorm.
 * User: dimi
 * Date: 2019-01-25
 * Time: 15:34
 */

class FachModel extends Model
{
    protected $tableName = 'fach';

    public function get_faecher_by_lehrer_id($lehrerID) {
        $query = "SELECT * FROM $this->tableName WHERE lehrer_id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $lehrerID);
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

    public function create_new_fach($titel, $klassen_id, $lehrer_id) {
        $query = "INSERT INTO $this->tableName (titel, klassen_id, lehrer_id) VALUES (?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('sii', $titel, $klassen_id, $lehrer_id);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }

    public function get_faecher_by_klassen($klassenIDs) {
        $inKlassen = rtrim(str_repeat('?,', count($klassenIDs)), ',');
        $query = "SELECT * FROM $this->tableName WHERE klassen_id IN ($inKlassen)";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $this->DynamicBindVariables($statement, $klassenIDs);
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

    public function read_with_ids($ids) {
        $inIds = rtrim(str_repeat('?,', count($ids)), ',');
        $query = "SELECT * FROM $this->tableName WHERE id IN ($inIds)";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $this->DynamicBindVariables($statement, $ids);
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

    private function DynamicBindVariables($stmt, $params, $params2 = null)
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