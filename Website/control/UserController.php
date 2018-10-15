<?php

require_once 'lib/session.php';
require_once 'view/View.php';
require_once 'control/PictureController.php';
require_once 'model/UserModel.php';

/**
 * Diese Klasse stellt den Controller für die Benutzer Verwaltung dar.
 * @author Petar Barisic
 */

class UserController {
	
	/**
	 * Funktion die die bestimmte Sessionvariablen in den View setzt.
	 * @param View $p_view, der View der befüllt werden soll
	 * @param SessionManager $p_session, das Session Manager Object das die Session verwaltet
	 */
	private function setSessionVarsToView($p_view, $p_session) {
		$p_view->isLogdin = $p_session->getIsLogdin();
		$p_view->userName = $p_session->username;
	}
	
	/**
	 * Diese Funktion zeigt den Benutzerverwaltungs View an 
	 */
	function showOptions() {
		$session = new SessionManager();
		$session->sessionLoad();
		$error = "";
		if( isset($_GET["error"]) ){
			$error = $_GET["error"];
		}
		
		$userID = $session->userId;
		$pictureModel = new PictureModel();
		$rows = $pictureModel->getByWhere("f_userID", $userID);
		$userView = new View("view/editUser.php");
		$this->setSessionVarsToView($userView, $session);
		$userView->config_error = $error;
		$userView->data = $rows;
		$userView->display();
	}
	
	/**
	 * Diese Funktion ändert das Passwort des Benutzers falls die Eingabe valid ist.
	 */
	function changePW() {
		$session = new SessionManager();
		$session->sessionLoad();
		$userModel = new UserModel();
		$userID = $session->userId;
		$passwd1 = $_POST["oldpasswd"];
		$passwd2 = $_POST["passwd"];
		$passwd3 = $_POST["passwdrep"];
		
		$result = $userModel->getByWhere("userID", $userID);
		if(! empty($result)){
			if( $result[0]->userID == $userID ) {
				// Hasehs vergleichen
				if( password_verify($passwd1, $result[0]->password) ) {
					//Prüfe ob beide Passwörter gleich sind
					if( Validator::validatePassword($passwd2, $passwd3) == false ) {
						header("Location:index.php?cont=User&action=showOptions&error=Passwörter nicht identisch");
					} else {
						//Schreibe neuen Benutzer in die DB
						$userModel->changePassword($userID, $passwd2);
						header("Location:index.php?cont=User&action=showOptions");
					}
				} else {
					// Falls Passwort falsch
					header("Location:index.php?cont=User&action=showOptions&error=Passwort ist nicht korrekt");
				}
			}
			else{
				//Falls username falsch ist es wahrscheidlich ein hackerangriff da nur eine gültige session dies machen kann.
				header("Location:hack.php");
			}
		}
		else {
			// Falls keine Daten vorhanden
			header("Location:index.php?cont=User&action=showOptions&error=Keine Daten");
		}
	}
	
		/**
	 * Diese Methode ist für das Login des Benutzers zuständig. 
	 * Im Falle eines Fehlers wird wieder auf die index.php umgeleitet.
	 */
	function login(){
		//Parameter holen
		$email = $_POST["email"];
		$passwd = $_POST["passwd"];
		$email = htmlspecialchars($email);
		$userModel = new UserModel();
		//Record aus der DB für den Benutzer holen
		$result = $userModel->getByWhere("username", $email);
		if(! empty($result)){
			if( $result[0]->userName == $email ) {
				
				// Hasehs vergleichen
				if( password_verify($passwd, $result[0]->password) ) {

					$session = new SessionManager();
					$session->sessionLoad();
					$session->userId = $result[0]->userID;
					$session->username = $result[0]->userName;
					$session->isLogdin = "true";
					header("Location:index.php");

				}
				else {
					// Falls Passwort falsch
					header("Location:index.php?loginerror=Falsches Login");
					
				}
			}
			else{
				//Falls username falsch
			header("Location:index.php&loginerror=Falsches Login");
			}
		}
		else {
			// Falls keine Daten vorhanden
			header("Location:index.php&loginerror=Falsches Login");
		}
	}

	
	/**
	 * Diese Funktion ist für das registrieren einen neuen Benutzers zuständig.
	 */
	function register(){
		$email = $_POST["email"];
		$passwd1 = $_POST["passwd"];
		$passwd2 = $_POST["passwdrep"];
		$validator = new Validator();
		$userModel = new UserModel();
		$counter = 0;
		$result = $userModel->getByWhere("username", $email);
		foreach( $result as $row) {
			$counter ++;
		}
		if( $counter == 0)
		{
			//Prüefe ob email valird
			if( Validator::isEmail($email ) == false ) {
				header("Location:index.php?cont=Login&action=register_form&error=Email nicht valid");
			}
			//Prüfe ob beide Passwörter gleich sind
			else if( Validator::validatePassword($passwd1, $passwd2) == false ) {
				header("Location:index.php?cont=Login&action=register_form&error=Passwörter nicht gleich");
			}
			else {
				//Schreibe neuen Benutzer in die DB
				$userModel->registerNewUser($email, $passwd1);
				header("Location:index.php");
			}
		}
		else {
			//Falls der Benutzer schon vorhanden ist.
			
			header("Location:index.php?cont=Login&action=register_form&error=Benutzer existiert schon");
			
		}
		
	}
	
	/**
	 * Diese Funktion ist für das ausloggen des Benutzers zuständig.
	 * Sie gibt dem Session object die Anweissung die Session zu zerstören.
	 * Danach wird auf die index.php weitergeleitet
	 */
	function logout(){
		$session = new SessionManager();
		$session->sessionLoad();
		$session->killSession();
		header("Location:index.php");
	}
	
	/**
	 * Diese Funktion zeigt den View mit dem Registrirungsformular an.
	 * Falls Fehler im GET request vorhanden sind werden diese auch angezeigt.
	 */
	function register_form() {
		$session = new SessionManager();
		$session->sessionLoad();
		$registerError = "";
		if( isset($_GET["error"])){
			$registerError = $_GET["error"];
		}
		$view = new View("view/register.php");
		$view->register_error = $registerError;
		$view->isLogdin = $session->getIsLogdin();
		$view->userName = $session->username;
		$view->display();
	}
	
}