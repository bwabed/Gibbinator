/**
 * Funktion um die eingegebenen Daten der Registrierung auf Vollständigkeit zu überpfrüfen
 * @return true falls valid, anderenfalls false
 */
function validateRegister() {
	var email = $("#emailreg").val();
	var password1 = $("#passwdreg").val();
	var password2 = $("#passwdrepreg").val();
	if(validateEmail(email)) {
		
		if(password1 == password2 && isNotEmpty(password1)) {
			return true;
		}
		else{
			$("#register_error").html("Passwörter sind nicht identisch");
			makeRedBorder('#passwdreg');
			makeRedBorder('#passwdrepreg');
		}
	}
	else{
		$("#register_error").html("Email nicht valid");
		makeRedBorder('#emailreg');
	}
	return false;
}


/**
 * Funktion um die eingegebenen Daten des Logins auf Vollständigkeit zu überpfrüfen
 * @return true falls valid, anderenfalls false
 */
function validateLogin() {
	var email = $("#emaillog").val();
	var password = $("#passwdlog").val();
	
	if(validateEmail(email)) {
		if(isNotEmpty(password)) {
			return true;
		}
	}
	makeRedBorder('#emaillog');
	makeRedBorder('#passwdlog');
	return false;
}


/**
 * Funktion um die eingegebenen Daten des Uploads auf Vollständigkeit zu überpfrüfen
 * @return true falls valid, anderenfalls false
 */
function validateUpload() {
	var title = $("#pictitle").val();
	var picPath = $("#picture").val();
	var category = $("#category").val();
	if(isNotEmpty(title) && isNotEmpty(picPath) && isNotEmpty(category)) {
		return true;
	}
	$("#upload_error").html("Bitte alle Felder ausfüllen");
	makeRedBorder('#pictitle');
	makeRedBorder('#picture');
	makeRedBorder('#category');
	return false;
}


/**
 * Funktion um die eingegebenen Daten des Kommentierens auf Vollständigkeit zu überpfrüfen
 * @return true falls valid, anderenfalls false
 */
function validateComment() {
	var comment = $("#comment").val();
	if(isNotEmpty(comment)) {
		return true;
	}
	makeRedBorder('#comment');
	return false;
}


/**
 * Funktion um die eingegebenen Daten der PAsswort-änderung auf Vollständigkeit zu überpfrüfen
 * @return true falls valid, anderenfalls false
 */
function validateChangePW() {
	var password1 = $("#oldpasswd").val();
	var password2 = $("#passwd").val();
	var password3 = $("#passwdrep").val();

	if(isNotEmpty(password1) && isNotEmpty(password2) && isNotEmpty(password3)) {
		return true;
	}
	makeRedBorder('#oldpasswd');
	makeRedBorder('#passwd');
	makeRedBorder('#passwdrep');
	return false;
}


/**
 * Funktion um einem Eingabefeld einen roten Rahmen zu verpassen 
 */
function makeRedBorder(p_element) {
	$(p_element).css({ "border": '#FF0000 1px solid'});
}


/**
 * Funktion um eine Email per Regex Pattern zu validieren
 * @return true falls valide Email anderenfalls false 
 */
function validateEmail(p_email) {
	//Quelle Regex ist stackoverflow
	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	
	if(re.test(p_email)) {
		return true;
	} else {
		return false;
	}
}

/**
 * Funktion um zu prüfen ob ein String leer ist.
 * @retrun true falls string nicht leer, anderenfalls false
 */
function isNotEmpty(p_string) {
	if(p_string.length > 0) {
		return true;
	} else {
		return false;
	}
}