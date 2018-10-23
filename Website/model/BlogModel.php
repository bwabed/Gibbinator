<?php

require_once 'lib/Model.php';

/**
 * Das UserModel ist zuständig für alle Zugriffe auf die Tabelle "user".
 *
 * Die Ausführliche Dokumentation zu Models findest du in der Model Klasse.
 */
class BlogModel extends Model
{
    /**
     * Diese Variable wird von der Klasse Model verwendet, um generische
     * Funktionen zur Verfügung zu stellen.
     */
    protected $tableName = 'blog';

    /**
     * Erstellt einen neuen benutzer mit den gegebenen Werten.
     *
     * Das Passwort wird vor dem ausführen des Queries noch mit dem SHA1
     *  Algorythmus gehashed.
     *

     *
     * @throws Exception falls das Ausführen des Statements fehlschlägt
     */
//     public function create($id_benutzer, $text)
//     {
//         $password = sha1($password);

//         $query = "INSERT INTO $this->tableName (id, id_benutzer, text, timestamp) VALUES (?, ?, ?, ?)";

//         $statement = ConnectionHandler::getConnection()->prepare($query);
//         $statement->bind_param('ssss', $firstName, $lastName, $email, $password);

//         if (!$statement->execute()) {
//             throw new Exception($statement->error);
//         }
//     }
}
