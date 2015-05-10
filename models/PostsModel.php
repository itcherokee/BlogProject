<?php
namespace MODELS;

class PostsModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getPostById($id)
    {
        $query = "SELECT id, title, text, date, visits  FROM posts WHERE id = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = $this->parseData($statement);

        return $result;
    }

    public function getPostsPerBlogWithLimitByDate($blog_name, $start_date, $end_date, $from, $page_size)
    {
        $query = "SELECT p.id, title, text, date, visits  FROM posts p "
            . "INNER JOIN users u ON p.user_id = u.id "
            . "WHERE u.username = ? AND p.date BETWEEN ? AND ? "
            . "ORDER BY p.date DESC LIMIT ?,?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("sssii", $blog_name, $start_date, $end_date, $from, $page_size);
        $statement->execute();
        $result = $this->parseData($statement);

        return $result;
    }

    public function getPostsPerBlogWithLimitByDateByTag($blog_name, $tag, $start_date, $end_date, $from, $page_size)
    {
        $query = "SELECT p.id, title, text, date, visits  FROM posts p "
            . "INNER JOIN users u ON p.user_id = u.id "
            . "INNER JOIN tags_posts tp ON p.id = tp.post_id "
            . "INNER JOIN tags t ON t.id = tp.tag_id "
            . "WHERE u.username = ? AND t.name = ? AND p.date BETWEEN ? AND ? "
            . "ORDER BY p.date DESC LIMIT ?,?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("ssssii", $blog_name, $tag, $start_date, $end_date, $from, $page_size);
        $statement->execute();
        $result = $this->parseData($statement);

        return $result;
    }

    public function countAllPostsPerBlog($username, $start_date, $end_date)
    {
        $query = "SELECT count(p.Id) FROM posts p "
            . "INNER JOIN users u ON p.user_id = u.id WHERE u.username = ? AND p.date BETWEEN ? AND ? ";
        $statement = $this->db->prepare($query);
        $statement->bind_param("sss", $username, $start_date, $end_date);
        $statement->execute();
        $result = null;
        $statement->bind_result($result);
        $statement->fetch();

        return $result;
    }

    public function countAllPostsPerBlogPerTag($username, $tag, $start_date, $end_date)
    {
        $query = "SELECT count(p.Id) FROM posts p "
            . "INNER JOIN users u ON p.user_id = u.id "
            . "INNER JOIN tags_posts tp ON p.id = tp.post_id "
            . "INNER JOIN tags t ON t.id = tp.tag_id "
            . "WHERE u.username = ? AND t.name = ? AND p.date BETWEEN ? AND ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("ssss", $username, $tag, $start_date, $end_date);
        $statement->execute();
        $result = null;
        $statement->bind_result($result);
        $statement->fetch();

        return $result;
    }

    public function CreatePost($title, $text, $date, $user_id)
    {
        $query = "INSERT INTO posts (title, text, date, user_id) "
            . "VALUES(?, ?, ?, ?)";
        $statement = $this->db->prepare($query);
        $statement->bind_param("sssi", $title, $text, $date, $user_id);
        $statement->execute();

        return $statement->insert_id;
    }

    public function getLoggedUserId($username)
    {
        $query = "SELECT id FROM users WHERE username = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = null;
        $statement->bind_result($result);
        $statement->fetch();

        return $result;
    }

    public function createTag($name)
    {
        $query = "INSERT INTO tags (name) VALUES(?)";
        $statement = $this->db->prepare($query);
        $statement->bind_param("s", $name);
        $statement->execute();

        return $statement->insert_id;
    }

    public function linkTagToPost($tag_id, $post_id)
    {
        $query = "INSERT INTO tags_posts (tag_id, post_id) VALUES(?, ?)";
        $statement = $this->db->prepare($query);
        $statement->bind_param("ii", $tag_id, $post_id);
        $statement->execute();

        return $statement->affected_rows > 0;
    }

    public function unlinkTagsFromPost($tag_id, $post_id)
    {
        $statement = $this->db->prepare("DELETE FROM tags_posts WHERE (tag_id, post_id) = (?,?)");
        $statement->bind_param("ii", $tag_id, $post_id);
        $statement->execute();

        return $statement->affected_rows > 0;
    }

    public function checkTagExists($tag)
    {
        $query = "SELECT id FROM tags WHERE name = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("s", $tag);
        $statement->execute();
        $result = null;
        $statement->bind_result($result);
        $statement->fetch();

        return $result;
    }

    public function deletePost($post_id)
    {
        $statement = $this->db->prepare("DELETE FROM posts WHERE id = ?");
        $statement->bind_param("i", $post_id);
        $statement->execute();

        return $statement->affected_rows > 0;
    }

    public function getAllTagsPerPost($post_id)
    {
        $query = "SELECT t.id, t.name FROM tags t "
            . "INNER JOIN tags_posts tp ON tp.tag_id = t.id WHERE tp.post_id = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("i", $post_id);
        $statement->execute();
        $result = $this->parseData($statement);

        return $result;
    }

    public function getAllCommentsPerPost($post_id)
    {
        $query = "SELECT c.id, c.text, c.username, c.useremail FROM comments c "
            . "INNER JOIN posts p ON c.post_id = p.id WHERE p.id = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("i", $post_id);
        $statement->execute();
        $result = $this->parseData($statement);

        return $result;
    }

    public function increasePostView($views, $id)
    {
        $query = "UPDATE posts SET visits = ? WHERE id = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("ii", $views, $id);

        $statement->execute();
    }

    public function getMostPopularTags($username)
    {
        $query = "SELECT t.name, t.id, count(tp.tag_id) as counts "
            . "FROM tags_posts tp "
            . "JOIN tags t ON tp.tag_id = t.id "
            . "JOIN posts p ON tp.post_id = p.id "
            . "JOIN users u ON u.id = p.user_id "
            . "WHERE u.username = ? "
            . "GROUP BY t.name "
            . "ORDER BY counts DESC "
            . "LIMIT 0, 5";
        $statement = $this->db->prepare($query);
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $this->parseData($statement);

        return $result;
    }

    public function getOldestAndNewestPostDate($username)
    {
        $query = "SELECT MIN(p.date) AS oldest, MAX(p.date) AS newest FROM posts p "
            . "INNER JOIN users u ON p.user_id = u.id WHERE u.username = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $this->parseData($statement);

        return $result;
    }

    public function getPostsHistorically($username)
    {
        $query = "SELECT count(p.id) AS counts, "
            . "EXTRACT(YEAR FROM p.date) AS year, "
            . "EXTRACT(MONTH FROM p.date) AS month, "
            . "EXTRACT(DAY FROM p.date) AS day "
            . "FROM posts p "
            . "JOIN users u ON u.id = p.user_id "
            . "WHERE u.username = ? "
            . "GROUP BY year, month, day "
            . "ORDER BY year DESC, month ASC";
        $statement = $this->db->prepare($query);
        $statement->bind_param("s", $username);
        $statement->execute();
        $fromDbResult = $this->parseData($statement);

        $result = array();
        foreach ($fromDbResult as $record) {
            if (!array_key_exists($record['year'], $result)) {
                $result[$record['year']] = array($record['month'] => $record['counts']);
            } else {
                if (array_key_exists($record['month'], $result[$record['year']])) {
                    $result[$record['year']][$record['month']] += $record['counts'];
                } else {
                    $result[$record['year']][$record['month']] = $record['counts'];
                }
            }
        }

        return $result;
    }

    public function updatePost($title, $text, $post_id)
    {
        $query = "UPDATE posts SET title = ?, text = ? WHERE id = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("ssi", $title, $text, $post_id);
        $statement->execute();

        return $statement->affected_rows > 0;
    }
}