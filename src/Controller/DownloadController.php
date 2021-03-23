<?php


namespace App\Controller;


use App\Authentication\Authentication;
use App\Authentication\UserTypes;

class DownloadController
{

    public function get_file() {
        Authentication::restrict_authenticated(UserTypes::$_PROF);
        if (isset($_GET['file']) && !empty($_GET['file'])) {
            $file = urldecode($_GET['file']);
            $filepath = "data/" . $file;

            // Process Download
            if (file_exists($filepath)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filepath));
                flush();
                readfile($filepath);
                exit;
            }
        }
    }
}