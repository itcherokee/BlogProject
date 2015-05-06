<?php
namespace MODELS;

class UsersModel extends BaseModel
{


    public function __construct()
    {
        parent::__construct();
    }

   // public function register($username, $password, $blogName)
    public function register($username, $password)
    {
        $statement = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $statement->get_result();
        if (count($result->fetch_all()) > 0) {
            return false;
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        //$statement2 = $this->db->prepare("INSERT INTO users (username, password, blog_name) VALUES (?, ?, ?)");
        $statement2 = $this->db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
//        $statement2->bind_param("sss", $username, $password_hash, $blogName);
        $statement2->bind_param("ss", $username, $password_hash);
        $statement2->execute();

        return true;
    }

    public function login($username, $password)
    {
        $statement = $this->db->prepare("SELECT password FROM users WHERE username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $statement->get_result();
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            return true;
        }

        return false;
    }
}