<?php

/**
 * Der Dispatcher ist dafür zuständig, alle Requests an den entsprechenden
 * Controller weiterzuleiten.
 *
 * Der Dispatcher schaut die URI des Requests an und leitet aufgrund davon die
 * Anfrage an die gewünschte Funktion im entsprechenden Controller weiter. Die
 * URI wird wie im folgenden Beispiel verarbeitet:
 *
 *   /user/delete?id=7&foo=bar
 *    |    |      └────┴─ GET Parameter stehen im Array $_GET zur Verfügung
 *    |    |
 *    |    └─ Der Wert nach dem zweiten Slash heisst so wie die Funktion, welche
 *    |         auf dem Contoller aufgeruft werden soll.
 *    |
 *    └─ Dem Wert nach dem ersten Slash wird noch "Controller" angehängt um
 *         herauszufinden, wie der gewünschte Controller heisst.
 *
 *   Sollte ein Teil in der URI nicht vorhanden sein, wird als Controllername
 *     "DefaultController" bzw. "index" als Funktionsname verwendet.
 */
class Dispatcher
{
    /**
     * Diese Methode wertet die Request URI aus leitet die Anfrage entsprechend
     * weiter.
     */
    public static function dispatch()
    {
        $url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

        $controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'DefaultController';
        $method         = !empty($url[1]) ? $url[1] : 'index';
        $args           = array_slice($url, 2);

        require_once ("controller/$controllerName.php");

        ob_start();

        $controller = new $controllerName();
        call_user_func_array(array($controller, $method), $args);
        unset($controller);

        $page = ob_get_contents();
        ob_end_clean();
        echo $page;

    }
}
