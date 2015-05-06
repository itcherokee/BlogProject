<?php

namespace MODELS;

class BaseModel
{
    protected $db = null;

    public function __construct()
    {
        $this->db = \MVC\Database::get_instance()->get_db();
    }

//    public function getUserBlog($username)
//    {
//        $statement = $this->db->prepare("SELECT blog_name FROM users WHERE username = ?");
//        $statement->bind_param("s", $username);
//        $statement->execute();
//        $result = $statement->get_result()->fetch_assoc();
//        if (count($result) > 0) {
//            return $result['blog_name'];
//        }
//
//        return null;
//    }

    public function isOwnerOfBlog($blogName, $username)
    {
        $statement = $this->db->prepare("SELECT username FROM users WHERE username = ?");
        $statement->bind_param("s", $blogName);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        if (count($result) > 0 && $result['username'] == $username) {
            return true;
        }

        return false;
    }


}