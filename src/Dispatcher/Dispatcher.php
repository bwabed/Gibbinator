<?php

namespace App\Dispatcher;

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
        $controllerName = UriParser::getControllerName().'Controller';
        $className = 'App\\Controller\\'.$controllerName;
        $methodName = UriParser::getMethodName();

        // Eine neue Instanz des Controllers wird erstellt und die gewünschte
        // Methode darauf aufgerufen.
        $controller = new $className();
        $controller->$methodName();
    }
}
