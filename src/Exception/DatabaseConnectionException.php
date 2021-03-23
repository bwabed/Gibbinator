<?php

namespace App\Exception;

use Exception;

class DatabaseConnectionException extends Exception
{
    public function __construct($errors)
    {
        $message = 'Verbindungsfehler zur Datenbank';
        $code = 0;
        $previous = null;

        parent::__construct($message, $code, $previous);
    }
}
