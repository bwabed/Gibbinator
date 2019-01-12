<?php

require_once 'lib/Model.php';

class UsertypeModel extends Model
{

    protected $tableName = 'usertype';

    public function getUsertypeById($id) {
        $query = "SELECT * FROM $this->tableName WHERE user_type = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $id);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }
}
