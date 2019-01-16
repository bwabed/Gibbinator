<?php

require_once 'lib/Model.php';
/**
 * Created by PhpStorm.
 * User: dimit
 * Date: 15.01.2019
 * Time: 11:46
 */

class GebaeudeModel extends Model
{
    protected $tableName = 'gebaeude';
    protected $floorTable = 'stockwerk';

    public function addBuilding($name, $street, $number, $plz, $ort) {
        $query = "INSERT INTO $this->tableName (bezeichnung, strasse, nr, plz, ort) VALUES (?, ?, ?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('sssis', $name, $street, $number, $plz, $ort);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }

    public function addStockwerk($name, $gebaeudeID, $floorNumber) {
        $query = "INSERT INTO $this->floorTable (gebaeude_ID, bezeichnung, nummer) VALUES (?, ?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('isi', $gebaeudeID, $name, $floorNumber);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }
}