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

    public function __construct($blog)
    {
        parent::__construct($blog);
        $this->pageSize = 3;
    }

    public function index($id = array())
    {
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

            //TODO: load all comments and allow "add comment button"

        } else {
            $this->isSinglePost = false;

            // check is it historical posts reqest
            if (isset($_GET['year']) && isset($_GET['month'])) {
                $dtime = \DateTime::createFromFormat("Y/m/d", $_GET['year'] . '/' . $_GET['month'] . '/1');
                $startDateTimestamp = $dtime->getTimestamp();
                $this->startDate = date("Y-m-d", $startDateTimestamp);
                $this->endDate = date("Y-m-t", $startDateTimestamp);
                $this->historyPeriod = 'year=' . $_GET['year'] . '&month=' . $_GET['month'];
            } else {
                $this->historyPeriod = '';
                $minMaxPosts = $this->modelData->getOldestAndNewestPostDate($this->blogName);
                $dtime = \DateTime::createFromFormat("Y-m-d", $minMaxPosts[0]['oldest']);
                $startDateTimestamp = $dtime->getTimestamp();
                $this->startDate = date("Y-m-d", $startDateTimestamp);
                $dtime = \DateTime::createFromFormat("Y-m-d", $minMaxPosts[0]['newest']);
                $startDateTimestamp = $dtime->getTimestamp();
                $this->endDate = date("Y-m-t", $startDateTimestamp);
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
    }

    public function create()
    {
        $this->authorize();
        $this->actionName = __FUNCTION__;

        if ($this->isPost) {
            $title = $_POST['title'];
            $text = $_POST['text'];
            $date = strftime("%Y-%m-%d", time());
            $postOwner = $this->modelData->getLoggedUserId($this->getUsername());
            $tags = preg_split('/,/', $_POST['tags'], -1, PREG_SPLIT_NO_EMPTY);
            if (!empty($title) && !empty($text) && !empty($date) && count($tags) > 0 && $postOwner != 0) {
                $postId = $this->modelData->createPost($title, $text, $date, $postOwner);
                if ($postId > 0) {
                    foreach ($tags as $tagName) {
                        $tagId = $this->modelData->checkTagExists($tagName);
                        if ($tagId == null) {
                            $tagId = $this->modelData->createTag($tagName);
                        }

                        if ($tagId > 0) {
                            if (!$this->modelData->linkTagToPost($tagId, $postId)) {
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

        $this->renderView();
    }


    public function delete($id)
    {
        $this->authorize();

        $this->actionName = __FUNCTION__;

        // check is there any comments linked -> check does checkbox allowinf delete of them is clicked -> yes delete all comments
        // find all linked Tags -> unlink them
        // delete post


//        if ($this->modelData->deletePost($id)) {
//            $this->addInfoMessage("Post deleted.");
//        } else {
//            $this->addErrorMessage("Cannot delete post - there are linked comments.");
//        }

        $this->renderView();
    }

    public function edit($id)
    {
        $this->authorize();

        $this->actionName = __FUNCTION__;

        $this->renderView();

    }

    public function search($tag)
    {
        $this->actionName = __FUNCTION__;
        echo 'tag not found';
        //$this->renderView();

    }
}