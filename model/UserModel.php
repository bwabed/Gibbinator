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
     * Das Passwort wird vor dem ausführen des Queries noch mit dem SHA1
     *  Algorythmus gehashed.
     *
     * @param $firstName Wert für die Spalte firstName
     * @param $lastName Wert für die Spalte lastName
     * @param $email Wert für die Spalte email
     * @param $password Wert für die Spalte password
     *
     * @throws Exception falls das Ausführen des Statements fehlschlägt
     */
    public function create($firstName, $lastName, $email, $password, $userType)
    {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO $this->tableName (vorname, nachname, email, password, user_type) VALUES (?, ?, ?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ssssi', $firstName, $lastName, $email, $password_hash, $userType);

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

    public function change_email($old_email,$new_email,$username) {
        $query = "UPDATE $this->tableName SET email = ? where benutzername = ? AND email = ?";
        $connection = ConnectionHandler::getConnection();
        $statement = $connection->prepare($query);
        $statement->bind_param('sss', $new_email, $username, $old_email);
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
        $query = "SELECT * FROM $this->tableName WHERE user_type = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $id);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }
}
