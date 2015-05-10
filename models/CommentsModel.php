<?php
namespace MODELS;

class CommentsModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createComment($text, $username, $email, $post_id)
    {
        $query = "INSERT INTO comments (text, username, useremail, post_id) VALUES(?, ?, ?, ?)";
        $statement = $this->db->prepare($query);
        $statement->bind_param("sssi", $text, $username, $email, $post_id);
        $statement->execute();

        return $statement->insert_id;
    }

    public function updateComment($id, $username, $useremail, $text)
    {
        $query = "UPDATE comments SET username = ?, useremail = ?, text = ? WHERE id = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("sssi", $username, $useremail, $text, $id);
        $statement->execute();

        return $statement->affected_rows > 0;
    }

    public function getCommentById($id)
    {
        $query = "SELECT id, username, useremail, text FROM comments WHERE id = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = $this->parseData($statement);

        return $result;
    }
}