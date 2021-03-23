<?php

namespace App\Exception;

use App\View\View;
use Throwable;

class ExceptionListener
{
    private function __construct()
    {
    }

    public static function register()
    {
        set_exception_handler(self::class.'::handleException');
    }

    public function handleException(Throwable $exception)
    {
        $error = $exception->getMessage();

        $view = new View('error');
        $view->title = 'Fehler';
        $view->heading = 'Ein Fehler ist aufgetretten';
        $view->userMessage = '';
        $view->exception = $exception;

        if ($exception instanceof DatabaseConnectionException) {
            $view->userMessage = 'Bitte kontaktieren Sie den Administrator';
        }

        $view->display();
    }
}
