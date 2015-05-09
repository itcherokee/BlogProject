<?php
namespace CONTROLLERS;
class HomeController extends BaseController
{

    public $blogs = array();
    protected $firstPage = 0;
    protected $lastPage = 0;

    public function __construct($blogName)
    {
        parent::__construct(SYSTEM_BLOG);
        $this->pageSize = 10;
        $this->lastPage = (int)floor($this->modelData->countAllBlogs() / $this->pageSize);
    }

    public function Index()
    {
        $this->currentPage = 0;
        if (isset($_GET['page'])) {
            $this->currentPage = $_GET['page'];
        }

        if ($this->currentPage < $this->firstPage){
            $this->currentPage = $this->firstPage;
        }

        if ($this->currentPage > $this->lastPage){
            $this->currentPage = $this->lastPage;
        }

        $from = $this->currentPage * $this->pageSize;
        $this->blogs = $this->modelData->getAllBlogsWithLimit($from, $this->pageSize);
        $this->renderView();
    }


}