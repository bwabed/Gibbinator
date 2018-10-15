<?php
/**
 * Diese Klasse wird verwendet um Benutzereigaben zu validieren.
 * Diese Klasse besteht nur aus statischen Funktionen so das keine Instanz dieser Klasse erzeugt werden muss.
 * @author Sascha Blank
 */

class Validator {
	
	/**
	 * Prüft ob es sich beim übergebenen String um eine E-Mail Adresse handelt.
	 * @param string $p_string, die vermeindliche E-Mail Adresse
	 * @return true falls email anderenfalls false
	 */
	static function isEmail($p_string) {
		if( !filter_var($p_string, FILTER_VALIDATE_EMAIL) ) {
			return false;
		}
		return true;
	}
	
	/**
	 * Diese Funktion überprüft ob der übergebene Wert eine Zahl ist.
	 * @param unknown_type $p_string, der zu validierende Wert
	 * @return true falls Zahl anderenfalls false
	 */
	static function isNumber($p_string) {
		if( !filter_var($p_string, FILTER_VALIDATE_INT) ){
			return false; 
		}
		return true;
	}
	
	/**
	 * Diese Funktion prüft ob die beiden Passwort Strings identisch sind oder nicht.
	 * @param unknown_type $p_passwd, das erste Password
	 * @param unknown_type $p_confirmPasswd, das zweite Passwort
	 * @return true wenn beide Passwörter identisch sind anderenfalls false.
	 */
	static function validatePassword($p_passwd,$p_confirmPasswd) {
		if( !strlen($p_passwd) >= 8) {
			return false;
		}
		if( $p_passwd != $p_confirmPasswd){
			return false;
		}
		return true;
	}
	
	/**
	 * Diese Methode überprüft ob der übergebene Wert den Wert 0 hat.
	 * @param unknown_type $p_categoryID, der zu prüfende Wert
	 * @return true wen der Wert des Wertes 0 ist oder anderefalls false.
	 */
	static function isFieldNotZero($p_field) {
		if($p_field != 0){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Diese Funktion prüft ob der String leer ist oder nicht.
	 * @param string $p_string, der zu prüfende String
	 * @return true falls string leer ist false anderenfalls.
	 */
	static function isStringEmpty($p_string){
		return strlen($p_string) > 0 ? false : true; 
	}
	
	/**
	 * Diese Funktion validiert alle Parameter eines POST requests. 
	 * @return true falls ein Parameter seltsame strings enthält anderenfals false(false == gut)
	 */
	static function validatePostRequest(){
		$foundSomthing = false;
		foreach ($_POST as $key => $value){
			$foundSomthing = Validator::validateVsHackyStuff($value);
			if( $foundSomthing ){
				return true;
			}
		}
		return $foundSomthing;
	}
	
	/**
	 * Diese Funktion validiert alle Parameter eines GET requests. 
	 * @return true falls ein Parameter seltsame strings enthält anderenfals false(false == gut)
	 */
	static function validateGetRequest(){
		$foundSomthing = false;
		foreach ($_POST as $key => $value){
			$foundSomthing = Validator::validateVsHackyStuff($value);
			if( $foundSomthing ){
				return true;
			}
		}
		return $foundSomthing;
	}
	
	/**
	 * Diese Funktion sucht nach strings die für eine SQL Injection oder XSS gedacht sind.
	 * @param string $p_field, das zu prüfende feld
	 * @return true falls etwas gefunden, anderenfalls false
	 */
	static function validateVsHackyStuff($p_field){
		if( stripos ($p_field, "<script>") !== false
			|| stripos($p_field, "</script>")!== false  
			|| stripos($p_field, "{")!== false
			|| stripos($p_field, "}") !== false){
			return true;	
		}
		if( strpos($p_field, "or 1=1")!== false
			|| stripos($p_field,"drop table") !== false
			|| stripos($p_field,"select * from user")!== false 
			|| stripos($p_field,"SELECT passwd FROM user")!== false
			|| stripos($p_field,"select passwd from user")!== false) {
			return true;
		}
		return false;
	}
}