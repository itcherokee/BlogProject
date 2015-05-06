<?php
namespace MODELS;

class PostsModel extends BaseModel
{


    public function __construct()
    {
        parent::__construct();
    }

    public function getAllPerBlog($blogName)
    {
        $query = "SELECT p.id, title, text, date, visits  FROM posts p "
            . "INNER JOIN users u ON p.user_id = u.id WHERE u.blog_name = ? ORDER BY p.date";
        $statement = $this->db->prepare($query);
        $statement->bind_param("s", $blogName);
        $statement->execute();
        $result = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    public function getAllPostsPerBlogWithLimit($blogName, $from, $pageSize)
    {
        $query = "SELECT p.id, title, text, date, visits  FROM posts p "
            . "INNER JOIN users u ON p.user_id = u.id WHERE u.username = ? ORDER BY p.date LIMIT ?,?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("sii", $blogName, $from, $pageSize);
        $statement->execute();
        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countAllPostsPerBlog($username)
    {
        $query = "SELECT count(p.Id) FROM posts p "
            . "INNER JOIN users u ON p.user_id = u.id WHERE u.username = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("s", $username);
        $statement->execute();
        return $statement->get_result()->fetch_row()[0];
    }

    public function CreatePost($title, $text, $date, $user_id)
    {
        $query = "INSERT INTO posts (title, text, date, user_id) "
            . "VALUES(?, ?, ?, ?)";
        $statement = $this->db->prepare($query);
        $statement->bind_param("sssi", $title, $text, $date, $user_id);
        $statement->execute();
        return $statement->affected_rows > 0;
    }

    public function getLoggedUserId($username)
    {
        $query = "SELECT id FROM users WHERE username = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("s", $username);
        $statement->execute();
        return $statement->get_result()->fetch_row()[0];
    }

    public function CreateTag($name)
    {
        $query = "INSERT INTO posts (name) VALUES(?)";
        $statement = $this->db->prepare($query);
        $statement->bind_param("s", $name);
        $statement->execute();
        return $statement->affected_rows > 0;
    }
}