<?php
/**
 * 
 * Diese Klasse stellt die Basisklasse des Models dar. Jedes Model leitet von dieser Klasse ab.
 * Die Klasse stellt Basisfunktionalitaet für den Datenbankzugriff zur Verfuegung.
 * @author Sascha Blank
 *
 */
class BaseModel {

	// Der zu verwendete Tabellenname
	protected $tableName;
	
	// Der zu verwendete Primaerschluessel
	protected $primarayKey;
	
	/**
	 * Konstruktor
	 * @param string $p_tablename, der mit der Klasse associierte Tabellennamen
	 * @param string $p_primaryKey, der Name des Primaerschluessel.
	 */
	function __construct( $p_tablename, $p_primaryKey  ) {
		$this->tableName = $p_tablename;
		$this->primarayKey = $p_primaryKey;		
	}
	
	/**
	 * Diese Funktion verbindet mir der Datenbank und gibt die Verbindung als Objekt zurueck.
	 * @author Sascha Blank
	 * @return object mit der Datanbankverbinndung.
	 */
 	function connectToDb() {
		$host		= "localhost";
	 	$username 	= "id7488181_gibbinatoradmin";
	 	$password	= "gibbiX12345";
	 	$dbName		= "id7488181_gibbinator";
		$conn = new mysqli($host, $username, $password, $dbName);
		if( $conn->connect_error ) {
			die("Connection failed: " . $conn->connect_error);
		}
		return $conn;
	}
	
	/**
	 * Diese Methode liest einen record, mit dem mitgegebenen Primaersschluessel( WHERE PK = ? )
	 * und gibt das Record zurueck
	 * @param string $p_pk, der Wert des Primaerschluessel
	 * @param string $p_attributes die gesuchten Attribute des records "*" fuer alle.
	 * @return object mit dem Record
	 */
	public  function getByPrimaryKey( $p_pk, $p_attributes ) {
		$query = "SELECT $p_attributes FROM $this->tableName WHERE $this->primarayKey = ?;";
		$conn = $this->connectToDb();
		$statement = $conn->prepare($query);
		$statement->bind_param('s',$p_pk);
		if( !$statement->execute()) {
			throw new Exception($statement->error);
		}
		$result = $statement->get_result();
		$row = $result->fetch_object();
		$conn->close(); 
		return $row;
	}
	
	/**
	 * Diese function gibt records anhand einer WHERE Bedinung zurueck
	 * @param string $p_whereAttribute, das Attribut der WHERE Bedinung
	 * @param string $p_whereValue, der Wert der WHERE Bedingung
	 * @return array mit records
	 */
	public function getByWhere($p_whereAttribute, $p_whereValue){
		$query = "SELECT * FROM $this->tableName WHERE $p_whereAttribute=?;";
		$conn = $this->connectToDb();
	 	$statement = $conn->prepare($query); 
		$statement->bind_param('s',$p_whereValue);
		if( !$statement->execute()) {
			throw new Exception($statement->error);
		}
		$result = $statement->get_result();
		$rows = array();
		while( $row = $result->fetch_object()) {
			$rows[] = $row;
		}
		$conn->close();
		return $rows;
	}
	
	/**
	 * Diese Function liest alle records einer Tabelle. Mit den Parameter kann eine maximal Zahl und ein Offset
	 * mitgeliefert werden.
	 * @param int $p_limit, die maximale records zahl die gelesenen werden soll
	 * @param int $p_offset, das offset zum Tabellenanfang
	 * @return array mit records
	 */
	public function readAll( $p_limit = 100, $p_offset = 0) {
		$query = "SELECT * FROM $this->tableName LIMIT $p_offset, $p_limit";
		$conn = $this->connectToDb();
		$statement = $conn->prepare($query);
		if( !$statement->execute()) {
			throw new Exception($statement->error);
		}
		$result = $statement->get_result();
		$rows = array();
		while( $row = $result->fetch_object()) {
			$rows[] = $row;
		}
		$conn->close();
		return $rows;
	}
	
	/**
	 * Diese Function loescht einen Datanbankeintrag anhand des Primaerschluessel.
	 * @param int $p_pk, der zu loeschende Primaerschluessel
	 * @throws Exception
	 */
	public function deleteEntry( $p_pk ) {
		$query = "DELETE FROM $this->tableName WHERE $this->primarayKey = ?;";
		$conn = $this->connectToDb();
		$statement = $conn->prepare($query);
		$statement->bind_param('i', $p_pk);
			if( !$statement->execute()) {
			throw new Exception($statement->error);
		}
		
	
	}
	
	
}