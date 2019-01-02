<?php
/*
 * Die Idee dieser Konfigurationsdateien ist, dass Konfigurationen, welche je
 * nach Umgebung varieren (z.B. Datenbankpasswort) zentral verwaltet werden
 * können. Dabei spielen die folgenden beiden Dateien eine Rolle.
 *
 *   config.example.php ist eine Beispielkonfiguration, welche Teil des Projekts
 *     ist und mit allen Entwicklern geteilt werden sollte. Erstelle darin eine
 *     Konfiguration, wie sie sein könnte und beschreibe, welche Wert wo
 *     konfguriert werden können.
 *
 *   config.php ist die Konfigurationsdatei, welche effektiv vom Framework
 *     verwendet wird. Wenn ein neuer Entwickler an diesem Projekt zu arbeiten
 *     beginnt, muss er die Datei config.example.php in diese Datei kopieren und
 *     die Konfiguration für sein System erstellen. Diese Datei ist persönlich
 *     und sollte mit niemandem geteilt werden.
 */

return array(

    // Datebankkonfiguration
    'database' => array(
        'host'     => 'DATABASE_HOST',
        'username' => 'DATABASE_USER',
        'password' => 'DATABASE_PASSWORD',
        'database' => 'DATABASE_NAME',
    ),

);
