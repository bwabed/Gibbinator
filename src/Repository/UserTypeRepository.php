<?php


namespace App\Repository;


use App\Database\ConnectionHandler;

class UserTypeRepository extends Repository
{
    protected $tableName = 'usertype';

    public function getUsertypeById($id) {
        $query = "SELECT * FROM $this->tableName WHERE id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $id);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }
}