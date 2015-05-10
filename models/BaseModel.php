<?php

namespace MODELS;

class BaseModel
{
    protected $db = null;

    public function __construct()
    {
        $this->db = \MVC\Database::get_instance()->get_db();
        $this->db->set_charset('UTF8');
    }

    public function isOwnerOfBlog($blogName, $username)
    {
        $statement = $this->db->prepare("SELECT username FROM users WHERE username = ?");
        $statement->bind_param("s", $blogName);
        $statement->execute();
        //$result = $statement->get_result()->fetch_assoc();
        $result = null; // = $this->parseData($statement);
        $statement->bind_result($result);
        $statement->fetch();
        if ($result != null && $result == $username) {
            return true;
        }

        return false;
    }

    protected function parseData($statement)
    {
        $meta = $statement->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = & $row[$field->name];
        }

        call_user_func_array(array($statement, 'bind_result'), $params);
        $result = array();
        while ($statement->fetch()) {
            foreach ($row as $key => $val) {
                $c[$key] = $val;
            }
            $result[] = $c;
        }

        return $result;
    }

    public function isBlogExists($blogName)
    {
        $statement = $this->db->prepare("SELECT username FROM users WHERE username = ?");
        $statement->bind_param("s", $blogName);
        $statement->execute();
        $result = null;
        $statement->bind_result($result);
        $statement->fetch();
        if ($result != null) {
            return true;
        }

        return false;
    }

    public function deleteComment($id)
    {
        $query = "DELETE FROM comments WHERE id = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("i", $id);
        $statement->execute();
        return $statement->affected_rows > 0;
    }
}