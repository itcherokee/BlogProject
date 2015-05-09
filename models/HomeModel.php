<?php
namespace MODELS;

class HomeModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct(array('table' => 'posts', 'limit' => 3));
    }

    public function getAllBlogsWithLimit($from, $pageSize)
    {
        $query = "SELECT id, username FROM users WHERE has_blog = TRUE ORDER BY username LIMIT ?,?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("ii", $from, $pageSize);
        $statement->execute();
        //$result = $statement->get_result()->fetch_all(MYSQLI_ASSOC);

       $result = $this->parseData($statement);

        return $result;
    }

    public function countAllBlogs() {
        $query = "SELECT count(Id) FROM users WHERE has_blog = TRUE";
        $statement = $this->db->query($query);
        return $statement->fetch_row()[0];
    }
}