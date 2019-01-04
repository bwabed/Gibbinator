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
    public function create($firstName, $lastName, $email, $password)
    {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO $this->tableName (firstName, lastName, email, password) VALUES (?, ?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ssss', $firstName, $lastName, $email, $password_hash);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }

    public function log_in($username, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "SELECT ID, email, password FROM $this->tableName WHERE email = ? AND password = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ss', $username, $hashed_password);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    public function change_password($old_password,$new_password,$username) {
        $old_password_hash = password_hash($old_password, PASSWORD_DEFAULT);
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE $this->tableName SET passwort = ? where benutzername = ? AND passwort = ?";
        $connection = ConnectionHandler::getConnection();
        $statement = $connection->prepare($query);
        $statement->bind_param('sss', $new_password_hash, $username, $old_password_hash);
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
}
