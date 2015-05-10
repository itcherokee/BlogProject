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

    public function updateComment($id, $text)
    {
        $query = "UPDATE comments SET text = ? WHERE id = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("si", $text, $id);
        $statement->execute();
    }
}