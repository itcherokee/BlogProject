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
        $this->lastPage = (int)floor($this->modelData->countAllPostsPerBlog($this->getUsername()) / $this->pageSize);

    }

    public function index()
    {
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
        $this->posts = $this->modelData->getAllPostsPerBlogWithLimit($this->blogName, $from, $this->pageSize);
        $this->renderView();
    }

    public function create()
    {
        $this->actionName = __FUNCTION__;

        if ($this->isPost) {
            $title = $_POST['title'];
            $text = $_POST['text'];
            //TODO: Add support for tags (check is exisiting, create
            $date = strftime("%Y-%m-%d", time());
            $postOwner = $this->modelData->getLoggedUserId($this->getUsername());

            if (!empty($title) && !empty($text) && !empty($date) && $postOwner != 0) {
                if ($this->modelData->createPost($title, $text, $date, $postOwner)) {
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
        $this->actionName = __FUNCTION__;

        $this->renderView();

    }

    public function edit($id)
    {
        $this->actionName = __FUNCTION__;

        $this->renderView();

    }


}