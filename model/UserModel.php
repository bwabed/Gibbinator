<?php

require_once 'lib/Model.php';

/**
 * Das UserModel ist zuständig für alle Zugriffe auf die Tabelle "user".
 *
 * Die Ausführliche Dokumentation zu Repositories findest du in der Model Klasse.
 */
class UserModel extends Model
{
    /**
     * Diese Variable wird von der Klasse Model verwendet, um generische
     * Funktionen zur Verfügung zu stellen.
     */
    protected $tableName = 'user';
    protected $userTypeTable = 'usertype';

    /**
     * Erstellt einen neuen benutzer mit den gegebenen Werten.
     *
     * Das Passwort wird nicht gehashed, da es ein Initialpasswort ist.
     *
     * @param $firstName Wert für die Spalte firstName
     * @param $lastName Wert für die Spalte lastName
     * @param $email Wert für die Spalte email
     * @param $password Wert für die Spalte password
     *
     * @throws Exception falls das Ausführen des Statements fehlschlägt
     */
    public function create_with_hash($firstName, $lastName, $email, $password, $userType, $init)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO $this->tableName (vorname, nachname, email, password, user_type, initial_pw) VALUES (?, ?, ?, ?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ssssis', $firstName, $lastName, $email, $hashed_password, $userType, $init);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }

    public function readAllExceptLoggedIn($userID, $max = 1000)
    {
        $query = "SELECT * FROM {$this->tableName} WHERE id != ? LIMIT 0, $max";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $userID);
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


    public function create_without_hash($firstName, $lastName, $email, $password, $userType, $init)
    {
        $query = "INSERT INTO $this->tableName (vorname, nachname, email, password, user_type, initial_pw) VALUES (?, ?, ?, ?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ssssis', $firstName, $lastName, $email, $password, $userType, $init);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }

    public function log_in($username) {
        $query = "SELECT * FROM $this->tableName WHERE email = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('s', $username);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    public function change_password($new_password,$userID) {
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE $this->tableName SET password = ? where id=?";
        $connection = ConnectionHandler::getConnection();
        $statement = $connection->prepare($query);
        $statement->bind_param('si', $new_password_hash, $userID);
        $statement->execute();
        return $connection->affected_rows;
    }

    public function update_password($new_password, $old_password, $userID) {
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $init_pw = 0;
        $query = "UPDATE $this->tableName SET password = ?, initial_pw = ? where id=? and password = ?";
        $connection = ConnectionHandler::getConnection();
        $statement = $connection->prepare($query);
        $statement->bind_param('siis', $new_password_hash, $init_pw, $userID, $old_password);
        $statement->execute();
        return $connection->affected_rows;
    }

    public function set_init_pw($new_password, $userID) {
        $query = "UPDATE $this->tableName SET password = ? where id=?";
        $connection = ConnectionHandler::getConnection();
        $statement = $connection->prepare($query);
        $statement->bind_param('si', $new_password, $userID);
        $statement->execute();
        return $connection->affected_rows;
    }

    public function change_email($old_email, $new_email, $userID)
    {
        $query = "UPDATE $this->tableName SET email = ? where id = ? AND email = ?";
        $connection = ConnectionHandler::getConnection();
        $statement = $connection->prepare($query);
        $statement->bind_param('sis', $new_email, $userID, $old_email);
        $statement->execute();
        return $connection->affected_rows;
    }

    public function check_if_email_exists($email) {
        $query = "SELECT email FROM $this->tableName WHERE email = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('s', $email);
        $statement->execute();
        $result = $statement->get_result();

        return $result;
    }

    public function getUsertypeById($id) {
        $query = "SELECT * FROM $this->userTypeTable WHERE id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $id);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    public function update_with_pw($userId, $email, $password, $vorname, $nachname, $usertype, $initPW) {
        if ($initPW == 0) {
            $password = password_hash($password, PASSWORD_DEFAULT);
        }
        $query = "UPDATE $this->tableName SET vorname = ?, nachname = ?, email = ?, password = ?, user_type = ?, initial_pw = ? where id = ?";
        $connection = ConnectionHandler::getConnection();
        $statement = $connection->prepare($query);
        $statement->bind_param('ssssiii', $vorname, $nachname, $email, $password, $usertype, $initPW, $userId);
        $statement->execute();
        return $connection->affected_rows;
    }

    public function update_without_pw($userId, $email, $vorname, $nachname, $usertype) {
        $query = "UPDATE $this->tableName SET vorname = ?, nachname = ?, email = ?, user_type = ? where id = ?";
        $connection = ConnectionHandler::getConnection();
        $statement = $connection->prepare($query);
        $statement->bind_param('sssii', $vorname, $nachname, $email, $usertype, $userId);
        $statement->execute();
        return $connection->affected_rows;
    }

    public function readAllProfs() {
        $userTypeProf = 2;
        $query = "SELECT * FROM $this->tableName WHERE user_type = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $userTypeProf);
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

    public function readAllStuds() {
        $userTypeStudent = 3;
        $query = "SELECT * FROM $this->tableName WHERE user_type = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $userTypeStudent);
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

    public function get_multiple_user_by_id($userIds) {
        $inKlassen = rtrim(str_repeat('?,', count($userIds)), ',');
        $query = "SELECT * FROM $this->tableName WHERE id IN ($inKlassen)";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement = $this->DynamicBindVariables($statement, $userIds);
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

    public function readAllStudsExceptIds($studIds) {
        $inStuds = rtrim(str_repeat('?,', count($studIds)), ',');
        $query = "SELECT * FROM $this->tableName WHERE id NOT IN ($inStuds) AND user_type = 3";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement = $this->DynamicBindVariables($statement, $studIds);
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
