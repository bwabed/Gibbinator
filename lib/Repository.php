<?php

require_once 'ConnectionHandler.php';

/**
 * Das Model ist das M in MVC. Es ist zustängig für alles, was mit der Datenbank
 * zu tun hat. Dazu gehört zum Beispiel:
 *   - Einzelner Datensatz aus der Datenbenk auslesen (SELECT...  WHERE id =)
 *   - Alle Datensätze, welche bestimmte Kriterien erfüllen aus der Datenbank
 *       auslesen. (SELECT mit WHERE)
 *   - Den Datensatz mit einer bestimmten id löschen
 *   - Einen neuen Datensatz erstellen.
 *   - Prüfen ob in der Benutzertabelle ein Datensatz mit dem vom Benutzer
 *       eingegebenen benutzernamen und passwort gibt.
 *
 * Die Idee ist hier, dass es für jede solche Operation auf dem Model eine
 * Funktion gibt. Diese Funktionen haben allenfalls Parameter (z.B. id des
 * auszulesenden Datensatzes) und im Normalfall auch einen Rückgabewert.
 * Rückgabe werte können sein:
 *   - Einzelner Datensatz (z.B. Datensatz bei id auslesen)
 *   - Array von Datensätzen (z.B. Suchresultate auslesen)
 *   - Boolscher wert, welcher z.B. sagt, ob ein Benutzer mit dem gegebenen
 *       Namen und Passwort existiert.
 *   - Kein Rückgebewert beim erstellen oder ändern eines Datensatzes
 *
 * -----------------------------------------------------------------------------
 * -- Implementation -----------------------------------------------------------
 * Für jede Tabelle in der Datenbank sollte es ein gleichnamiges Model geben.
 * Für die Tabelle user also eine Klasse UserModel (im Verzeichnis model),
 * welche von der Klasse Model erbt (diese hier). Eine minimale Model
 * implementation sieht folgendermassen aus:
 *
 *    <?php
 *    require_once 'lib/Model.php';
 *
 *    class UserModel extends Model
 *    {
 *      protected $tableName = 'user';
 *    }
 *
 * Die Vererbung macht daher sinn, dass in der Model Klasse Funktionen
 * implementiert werden, welche für alle Tabellen (fast) gleich sind. So müssen
 * diese nicht in jedem Model einzel implementiert werten. Ein Beispiel darür
 * ist das auslesen eines Datensatzes bei dessen id (siehe readById unten). So
 * könnte mit dem obigen Model bereits ein Bentzer bei dessen id ausgelesen
 * werden.
 *
 *   require_once 'model/UserModel.php'
 *
 *   [...]
 *
 *   $wantedId = $_GET['id'];
 *
 *   $model = new UserModel();
 *   $user = $model->readById($wantedId);
 *
 *   // User für die Darstellung der View übergeben
 *
 * Alle Funktionen, welche nich für alle Tabellen funktionieren (z.B. Datensatz
 * erstellen, da die Spalten immer unterschiedlich heissen), werden dann im
 * konkreten Model (z.B. UserModel) implementiert.
 *
 * Um eine Verbindung auf die Datenbank zu bekommen, hilft der
 * ConnectionHandler. Siehe dessen Information für genaueres.
 *
 * -----------------------------------------------------------------------------
 * -- Errorhandling ------------------------------------------------------------
 * Sollten bei der Interaktion mit der Datenbank Fehler auftreten, macht es
 * sinn, Exceptions zu werfen.
 *
 *   $statement = ...
 *   if (!$statement->execute()) {
 *     throw new Exception("Ein Fehler ist aufgetreten: $result->error");
 *   }
 */
class Repository
{
    /**
     * Damit die generischen Querys wisse, um welche Tabelle es sich handelt,
     * gibt es diese Variabel. Diese muss in den konkreten Implementationen mit
     * dem Tabellennamen überschrieben werden. (Siehe beispiel oben).
     */
    protected $tableName = null;

    /**
     * Diese Funktion gibt den Datensatz mit der gegebenen id zurück.
     *
     * @param $id id des gesuchten Datensatzes
     *
     * @throws Exception falls das Ausführen des Statements fehlschlägt
     *
     * @return Der gesuchte Datensatz oder null, sollte dieser nicht existieren.
     */
    public function readById($id)
    {
        // Query erstellen
        $query = "SELECT * FROM {$this->tableName} WHERE id=?";

        // Datenbankverbindung anfordern und, das Query "preparen" (vorbereiten)
        // und die Parameter "binden"
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $id);

        // Das Statement absetzen
        $statement->execute();

        // Resultat der Abfrage holen
        $result = $statement->get_result();
        if (!$result) {
            throw new Exception($statement->error);
        }

        // Ersten Datensatz aus dem Reultat holen
        $row = $result->fetch_object();

        // Datenbankressourcen wieder freigeben
        $result->close();

        // Den gefundenen Datensatz zurückgeben
        return $row;
    }

    /**
     * Diese Funktion gibt ein array mit allen Datensätzen aus der Tabelle
     * zurück.
     *
     * @param $max Wie viele Datensätze höchstens zurückgegeben werden sollen
     *               (optional. standard 100)
     *
     * @throws Exception falls das Ausführen des Statements fehlschlägt
     *
     * @return Ein array mit den gefundenen Datensätzen.
     */
    public function readAll($max = 100)
    {
        $query = "SELECT * FROM {$this->tableName} LIMIT 0, $max";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->execute();

        $result = $statement->get_result();
        if (!$result) {
            throw new Exception($statement->error);
        }

        // Datensätze aus dem Resultat holen und in das Array $rows speichern
        $rows = array();
        while ($row = $result->fetch_object()) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Diese Funktion löscht den Datensatz mit der gegebenen id.
     *
     * @param $id id des zu löschenden Datensatzes
     *
     * @throws Exception falls das Ausführen des Statements fehlschlägt
     */
    public function deleteById($id)
    {
        $query = "DELETE FROM {$this->tableName} WHERE id=?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $id);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }
}
