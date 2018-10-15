<?php
require_once 'model/BaseModel.php';

/**
 * Diese Klasse stellt die User Tablle als Model dar
 * Diese Klasse erbt von BaseModel
 * @author Sascha Blank
 */

class UserModel extends BaseModel {
	
	/**
	 * Standart Konstruktor
	 */
	function __construct(){
		BaseModel::__construct("user","userID");
	}
	
	/**
	 * Diese Funktion schreibt einen neuen Eintrag in die User Tabelle
	 * @param string $p_email, die email Addresse des Benutzers
	 * @param string $p_passwd, das Password des Benutzers(Das Password wird hier gehasht) 
	 */
	public function registerNewUser($p_email,$p_passwd ){
        $query = "INSERT INTO $this->tableName (username, password, privileges ) VALUES (?, ?, ?);";
        $conn = $this->connectToDb();
        $statement = $conn->prepare($query);
        $hash =  password_hash($p_passwd,PASSWORD_BCRYPT);
        $priv = "1";
        $statement->bind_param('sss', $p_email, $hash, $priv);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
        $conn->close();
	}
	
	
	/**
	 * Diese Funktion schreibt ein neues Passwort für einen Benutzer in die Datenbank
	 * @param int $p_userID, die UserID für die das Passwort geändert werden soll
	 * @param string $p_passwd, das neue Passwort. Das Passwort wird hier gehasht.
	 */
	public function changePassword($p_userID,$p_passwd ){
		$query = "UPDATE $this->tableName SET password=? WHERE userID=?;";
		$conn = $this->connectToDb();
		$statement = $conn->prepare($query);
		$hash =  password_hash($p_passwd,PASSWORD_BCRYPT);
		$statement->bind_param('ss', $hash, $p_userID);
		if (!$statement->execute()) {
			throw new Exception($statement->error);
		}
		$conn->close();
	}
}