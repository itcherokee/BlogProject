<?php
namespace MODELS;

class HomeModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllBlogsWithLimit($from, $page_size)
    {
        $query = "SELECT id, username FROM users WHERE has_blog = TRUE ORDER BY username LIMIT ?,?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("ii", $from, $page_size);
        $statement->execute();
        $result = $this->parseData($statement);

        return $result;
    }

    public function countAllBlogs()
    {
        $query = "SELECT count(Id) FROM users WHERE has_blog = TRUE";
        $statement = $this->db->query($query);

        return $statement->fetch_row()[0];
    }
}