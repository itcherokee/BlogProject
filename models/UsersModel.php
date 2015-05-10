<?php
namespace MODELS;

class UsersModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function register($username, $password, $with_blog)
    {
        $statement = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();
        $statement->bind_result($result);
        if ($statement->fetch() > 0) {
            return false;
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $statement2 = $this->db->prepare("INSERT INTO users (username, password, has_blog) VALUES (?, ?, ?)");
        $statement2->bind_param("ssi", $username, $password_hash, $with_blog);
        $statement2->execute();

        return $statement2->affected_rows > 0;
    }

    public function login($username, $password)
    {
        $statement = $this->db->prepare("SELECT password FROM users WHERE username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();
        $user = $this->parseData($statement);

        if (password_verify($password, $user[0]['password'])) {
            return true;
        }

        return false;
    }
}