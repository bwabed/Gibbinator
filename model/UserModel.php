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
}
