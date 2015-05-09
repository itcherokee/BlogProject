<?php
namespace CONTROLLERS;
class PostsController extends BaseController
{
    protected $posts = array();
    protected $firstPage = 0;
    protected $lastPage = 0;

    public function __construct($blog)
    {
        parent::__construct($blog);
        $this->pageSize = 3;
        $this->lastPage = (int)floor($this->modelData->countAllPostsPerBlog($blog) / $this->pageSize);

    }

    public function index($id = array())
    {
        //TODO : fix issue when sending index of blog for viewing


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

        if (count($id) === 0) {
            $from = $this->currentPage * $this->pageSize;
            $this->posts = $this->modelData->getAllPostsPerBlogWithLimit($this->blogName, $from, $this->pageSize);
        } else {
            $this->posts = $this->modelData->getPostById($id[0]);
            $this->modelData->increasePostView($this->posts[0]['visits'] + 1 ,$id[0]);

        }

        foreach ($this->posts as $key => $post) {
            $tags = $this->modelData->getAllTagsPerPost($post['id']);
            if (count($tags) > 0) {
                $combinedTags = array();
                foreach ($tags as $tag) {
                    $combinedTags[] = $tag['name'];
                }
                $tags = implode(', ', $combinedTags);
                $post['tags'] = $tags;

                if (count($id) === 0) {
                    $post['title'] = mb_substr($post['title'], 0, 70) . '...';
                    $post['text'] = mb_substr($post['text'], 0, 100) . '...';
                } else {
                    $post['visits'] = $post['visits'] + 1;
                }

                $this->posts[$key] = $post;
            }
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


    public
    function delete($id)
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

    public
    function edit($id)
    {
        $this->authorize();

        $this->actionName = __FUNCTION__;

        $this->renderView();

    }


}