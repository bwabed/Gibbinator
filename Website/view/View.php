<?php

/**
 * Diese Klasse stellt den View dar.
 * Da meistens immer die Navigation und das Login dargestellt werden muss werden diese auch fast immer gezeichnet.
 * Jede View Instanz wird eine View im Konstruktor zugewiesen.
 * @author Sascha Blank
 */

class View {
	
	//Die php Datei mit der View
	private $viewFile;
	
	//Die Propertys die vom Controller gesetzt werden
	private $properties = array();
	
	/**
	 * Konstruktor	
	 * @param string $p_viewFile, pfad zur View php Datei
	 */
	function __construct( $p_viewFile) {
		$this->viewFile = $p_viewFile;
	}
	
	/**
	 * Php Magic funktion um Propertys zu setzten
	 * @param string $p_key, der Schlüssel für den Wert
	 * @param string/int $p_value, der zu setzende Wert
	 */
    public function __set($p_key, $p_value)
    {
        if (!isset($this->$p_key)) {
            $this->properties[$p_key] = $p_value;
        }
    }

    /**
     * Php Magic Function.
     * @param unknown_type $p_key, der zu suchende Schlüssel. 
     * @return der Wert des associerten Schlüssel oder ein leerer string falls Schlüssel nicht gefunden
     */
    public function __get($p_key)
    {
        if (isset($this->properties[$p_key])) {
            return $this->properties[$p_key];
        }
        return "";
    }
	
    /**
     * Funktion die den View "zeichnet". Mit den Paramtern kann man festlegen welche Teile man "zeichnen" will.
     * @param boolean $p_showAll, wenn true dann zeige alles. Also Navigation, Login, und Content.  
     * @param boolean $p_showContent, wen true zeige den Content, bei falls Content nicht "zeichnen".
     */
	function display($p_showAll = true,$p_showContent = true) {
		if( !empty($this->properties)) {
			extract($this->properties);
		}
		if( $p_showAll ) {
			$loginError = "";
			
			if (isset($_GET['loginerror'])) {
				$loginError = $_GET["loginerror"];
			}
			require_once 'view/login.php';
		}
		if( $p_showContent ){
        	require_once $this->viewFile;
		}
	}
}