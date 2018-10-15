<?php
/**
 * Diese Klasse verwaltet die Session und schreibt/liest Werte in das Session Objekt von PHP
 * @author Sascha Blank
 */

class SessionManager{
	
	/**
	 * Diese Funktion prüft ob eine Session schon erstellt worden ist. 
	 * Falls nicht wird ein neues Session Objekt erstllt.
	 */
	function sessionLoad() {
		if( session_status() == PHP_SESSION_NONE ) {
			session_start();
		}
	}
	
	/**
	 * Zerstört das Session Objekt. 
	 * Wird für das ausloggen eines Benutzer benötigt
	 */
	function killSession(){
		session_unset(); 
		session_destroy(); 
	}
	
	/**
	 * Php Magic Funktion
	 * @param unknown_type $p_key, der Schlüssel des Wertes in der Session
	 * @param unknown_type $p_value, der Wert in der Session
	 */
	function __set($p_key, $p_value) {
		$_SESSION[$p_key] = $p_value;
	}
	
	/**
	 * Php Magic Function. Gibt den Wert zum ensprechenden Schlüssel zurück.
	 * Falls der Schlüssel nicht gefunden wurde wird ein leerer String zurückgegeben.
	 * @param unknown_type $p_key, der gesuchte Schlüssel
	 */
	function __get($p_key){
		if( isset( $_SESSION[$p_key] ) ) {
			return $_SESSION[$p_key];
		}
		return "";
	}
	
	/**
	 * Funktion um zu prüfen ob die Session eigelogt ist oder nicht
	 * @return true, wenn Session eigelogt anderenfalls false
	 */
	function getIsLogdin() {
		if( isset($_SESSION["isLogdin"] ) ) {
			if( $_SESSION["isLogdin"] == "true") {
				return true;
			}
		}
		return false;
	}
}