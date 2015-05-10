<?php
namespace CONTROLLERS;
class PostsController extends BaseController
{
    protected $posts = array();
    protected $firstPage = 0;
    protected $lastPage = 0;
    protected $isSinglePost = false;
    protected $mostPopularTags = null;
    protected $historyList = null;
    protected $historyPeriod = '';
    protected $startDate;
    protected $endDate;

    public function __construct($blog_name)
    {
        parent::__construct($blog_name);
        $this->pageSize = 3;
    }

    public function index($id = array())
    {
        if ($this->blogName != SYSTEM_BLOG && $this->blogName != ADMIN_BLOG) {
            // loads all posts historically by year, by month, by day
            $this->historyList = $this->modelData->getPostsHistorically($this->blogName);

            // loads most popular tags
            $this->mostPopularTags = $this->modelData->getMostPopularTags($this->blogName);

            // check is it single post or multiple posts request
            if (count($id) > 0) {
                $this->isSinglePost = true;
                $this->lastPage = 0;
                $this->posts = $this->modelData->getPostById($id[0]);
                $this->modelData->increasePostView($this->posts[0]['visits'] + 1, $id[0]);
            } else {
                $this->isSinglePost = false;

                // check is it historical posts reqest
                if (isset($_GET['year']) && isset($_GET['month'])) {
                    $date_time = \DateTime::createFromFormat("Y/m/d", $_GET['year'] . '/' . $_GET['month'] . '/1');
                    $startDateTimestamp = $date_time->getTimestamp();
                    $this->startDate = date("Y-m-d", $startDateTimestamp);
                    $this->endDate = date("Y-m-t", $startDateTimestamp);
                    $this->historyPeriod = 'year=' . $_GET['year'] . '&month=' . $_GET['month'];
                } else {
                    $this->historyPeriod = '';
                    $minMaxPosts = $this->modelData->getOldestAndNewestPostDate($this->blogName);
                    if ($minMaxPosts[0]['newest'] != null) {
                        $date_time = \DateTime::createFromFormat("Y-m-d", $minMaxPosts[0]['oldest']);
                        $startDateTimestamp = $date_time->getTimestamp();
                        $this->startDate = date("Y-m-d", $startDateTimestamp);
                        $date_time = \DateTime::createFromFormat("Y-m-d", $minMaxPosts[0]['newest']);
                        $startDateTimestamp = $date_time->getTimestamp();
                        $this->endDate = date("Y-m-t", $startDateTimestamp);
                    } else {
                        $current_date = time();
                        $this->startDate = date("Y-m-d", $current_date);
                        $this->endDate = date("Y-m-t", $current_date);
                    }
                }

                $this->lastPage = (int)floor($this->modelData->countAllPostsPerBlog($this->blogName, $this->startDate, $this->endDate) / $this->pageSize);

                // calculate navigation
                $this->currentPage = 0;
                if (isset($_GET['page'])) {
                    $this->currentPage = $_GET['page'];
                }

                if ($this->currentPage < $this->firstPage) {
                    $this->currentPage = $this->firstPage;
                }

                if ($this->currentPage > $this->lastPage) {
                    $this->currentPage = $this->lastPage;
                }

                $from = $this->currentPage * $this->pageSize;
                $this->posts = $this->modelData->getPostsPerBlogWithLimitByDate($this->blogName, $this->startDate, $this->endDate, $from, $this->pageSize);
            }

            foreach ($this->posts as $key => $post) {
                if ($this->isSinglePost) {
                    $post['visits'] = $post['visits'] + 1;
                    $comments = $this->modelData->getAllCommentsPerPost($post['id']);
                    if (count($comments) > 0) {
                        $post['comments'] = $comments;
                    }
                } else {
                    $post['title'] = mb_substr($post['title'], 0, 70) . '...';
                    $post['text'] = mb_substr($post['text'], 0, 100) . '...';
                }

                $tags = $this->modelData->getAllTagsPerPost($post['id']);
                if (count($tags) > 0) {
                    $combinedTags = array();
                    foreach ($tags as $tag) {
                        $combinedTags[] = $tag['name'];
                    }

                    $tags = implode(', ', $combinedTags);
                    $post['tags'] = $tags;
                }

                $this->posts[$key] = $post;
            }

            $this->renderView();
        } else {
            $this->redirect($this->blogName, DEFAULT_CONTROLLER, DEFAULT_ACTION);
        }
    }

    public function create()
    {
        $this->authorize();
        $this->actionName = __FUNCTION__;

        if ($this->isPost) {
            if (!isset($_POST['formToken']) || $_POST['formToken'] != $_SESSION['formToken']) {
                throw new \Exception('Invalid request!');
                exit;
            }

            $title = trim($_POST['title']);
            $text = trim($_POST['text']);
            $date = strftime("%Y-%m-%d", time());
            $user = $this->getUsername();
            if ($user == 'admin'){
                $user = strtolower($this->blogName);
            }

            $post_owner = $this->modelData->getLoggedUserId($user);
            $tags = preg_split('/,\s+/', $_POST['tags'], -1, PREG_SPLIT_NO_EMPTY);
            if (!empty($title) && !empty($text) && !empty($date) && count($tags) > 0 && $post_owner != 0) {
                $post_id = $this->modelData->createPost($title, $text, $date, $post_owner);
                if ($post_id > 0) {
                    foreach ($tags as $tag_name) {
                        $tag_name = strtolower(trim($tag_name));
                        $tag_id = $this->modelData->checkTagExists($tag_name);
                        if ($tag_id == null) {
                            $tag_id = $this->modelData->createTag($tag_name);
                        }

                        if ($tag_id > 0) {
                            if (!$this->modelData->linkTagToPost($tag_id, $post_id)) {
                                $this->addErrorMessage("Error linking tag to post.");
                            }
                        } else {
                            $this->addErrorMessage("Error - tag exist.");
                        }
                    }

                    $this->addInfoMessage("Post added.");
                    $this->redirect($this->blogName, $this->controllerName, DEFAULT_ACTION);
                } else {
                    $this->addErrorMessage("Error adding post.");
                }
            } else {
                $this->addErrorMessage("All fields must have a value!");
            }
        }

        $_SESSION['formToken'] = uniqid(mt_rand(), true);
        $this->renderView();
    }

    public function delete($params)
    {
        $this->authorize();

        $post_id = $params[0];
        $this->actionName = __FUNCTION__;

        $tags = $this->modelData->getAllTagsPerPost($post_id);

        if (count($tags) > 0) {
            foreach ($tags as $tag) {
                if ($this->modelData->unlinkTagsFromPost($tag['id'], $post_id) < 0) {
                    $this->addErrorMessage("Could not delete linked tags from post");
                    $parameters[] = $post_id;
                    $this->redirect($this->blogName, $this->controllerName, $this->actionName, $parameters);
                }
            }
        }

        $comments = $this->modelData->getAllCommentsPerPost($post_id);

        if (count($comments) > 0) {
            foreach ($comments as $comment) {
                if ($this->modelData->deleteComment($comment['id']) < 0) {
                    $this->addErrorMessage("Could not delete linked comment from post");
                    $parameters[] = $post_id;
                    $this->redirect($this->blogName, $this->controllerName, $this->actionName, $parameters);
                }
            }
        }

        if ($this->modelData->deletePost($post_id) > 0) {
            $this->addInfoMessage("post deleted.");
        } else {
            $this->addErrorMessage("Error deleting post");
        }

        $this->redirect($this->blogName, 'Posts', DEFAULT_ACTION);
    }

    public function edit($params)
    {
        $this->authorize();
        $post_id = $params[0];
        $this->actionName = __FUNCTION__;
        $this->post = $this->modelData->getPostById($post_id)[0];
        //TODO: include editing of tags for post

        if ($this->isPost) {
            if (!isset($_POST['formToken']) || $_POST['formToken'] != $_SESSION['formToken']) {
                throw new \Exception('Invalid request!');
                exit;
            }

            $title = trim($_POST['title']);
            $text = trim($_POST['text']);

            //$tags = preg_split('/,\s+/', $_POST['tags'], -1, PREG_SPLIT_NO_EMPTY);
            if (!empty($title) && !empty($text)) {
                if ($this->modelData->updatePost($title, $text, $post_id) > 0) {
                    $this->addInfoMessage("Post edited.");
                    $parameters[] = $post_id;
                    $this->redirect($this->blogName, $this->controllerName, DEFAULT_ACTION, $parameters);
                } else {
                    $this->addErrorMessage("Error editing post.");
                }
            } else {
                $this->addErrorMessage("All fields must have a value!");
            }
        }

        $_SESSION['formToken'] = uniqid(mt_rand(), true);
        $this->renderView();
    }

    public function search()
    {
        $this->actionName = __FUNCTION__;
        if (empty($_POST['tag'])) {
            $this->redirect($this->blogName, DEFAULT_CONTROLLER, DEFAULT_ACTION);
        } else {
            $tag = trim($_POST['tag']);
        }

        $this->historyList = $this->modelData->getPostsHistorically($this->blogName);
        $this->mostPopularTags = $this->modelData->getMostPopularTags($this->blogName);
        $this->isSinglePost = false;
        $this->historyPeriod = '';
        $min_max_posts = $this->modelData->getOldestAndNewestPostDate($this->blogName);

        if ($min_max_posts[0]['newest'] != null) {
            $date_time = \DateTime::createFromFormat("Y-m-d", $min_max_posts[0]['oldest']);
            $startDateTimestamp = $date_time->getTimestamp();
            $this->startDate = date("Y-m-d", $startDateTimestamp);
            $date_time = \DateTime::createFromFormat("Y-m-d", $min_max_posts[0]['newest']);
            $startDateTimestamp = $date_time->getTimestamp();
            $this->endDate = date("Y-m-t", $startDateTimestamp);
        } else {
            $current_date = time();
            $this->startDate = date("Y-m-d", $current_date);
            $this->endDate = date("Y-m-t", $current_date);
        }

        $this->lastPage = (int)floor($this->modelData->countAllPostsPerBlogPerTag($this->blogName, $tag, $this->startDate, $this->endDate) / $this->pageSize);

        $this->currentPage = 0;
        if (isset($_GET['page'])) {
            $this->currentPage = $_GET['page'];
        }

        if ($this->currentPage < $this->firstPage) {
            $this->currentPage = $this->firstPage;
        }

        if ($this->currentPage > $this->lastPage) {
            $this->currentPage = $this->lastPage;
        }

        $from = $this->currentPage * $this->pageSize;
        $this->posts = $this->modelData->getPostsPerBlogWithLimitByDateByTag($this->blogName, $tag, $this->startDate, $this->endDate, $from, $this->pageSize);

        foreach ($this->posts as $key => $post) {
            if ($this->isSinglePost) {
                $post['visits'] = $post['visits'] + 1;
            } else {
                $post['title'] = mb_substr($post['title'], 0, 70) . '...';
                $post['text'] = mb_substr($post['text'], 0, 100) . '...';
            }

            $tags = $this->modelData->getAllTagsPerPost($post['id']);
            if (count($tags) > 0) {
                $combined_tags = array();
                foreach ($tags as $tag) {
                    $combined_tags[] = $tag['name'];
                }

                $tags = implode(', ', $combined_tags);
                $post['tags'] = $tags;
            }

            $this->posts[$key] = $post;
        }

        $this->renderView('index');
    }
}