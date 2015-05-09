<?php
namespace CONTROLLERS;
abstract class BaseController
{
    private $routeToViews;
    private $templateName;
    protected $controllerName = DEFAULT_CONTROLLER;
    protected $actionName = DEFAULT_ACTION;
    protected $modelData = null;
    protected $title;
    protected $isPost = false;
    protected $session = null;
    protected $blogName = null;

    protected $pageSize = 3;

    public function __construct($blogName)
    {
        $this->blogName = $blogName;
        $this->controllerName = $this->getControllerName();
        $modelFile = '\\MODELS\\' . $this->controllerName . 'Model';
        $preTitle = $this->blogName != null ? $this->blogName . ' - ' : '';
        $this->title = $preTitle . $this->controllerName;
        $this->modelData = new $modelFile();
        //$this->session = $app->getSession();
        session_set_cookie_params(1800, "/");

        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->isPost = true;
        } else {
            $this->isPost = false;
        }
    }

    public function index()
    {
    }

    public function renderView($otherAction = null, $includeLayout = true)
    {
        // if (!$this->isViewRendered) {
        $selectedAction = $otherAction == null ? strtolower($this->actionName) : strtolower($otherAction) ;
        $viewFileName = 'views/' . strtolower($this->controllerName) . '/' . $selectedAction . '.php';
        if ($includeLayout) {
            $headerFile = 'views/layouts/header.php';
            include_once($headerFile);
        }

        include_once($viewFileName);
        if ($includeLayout) {
            $footerFile = 'views/layouts/footer.php';
            include_once($footerFile);
        }
        $this->isViewRendered = true;
        //  }
    }

    public function redirectToUrl($url)
    {
        header("Location: " . $url);
        die;
    }

    public function redirect($blog, $controllerName, $actionName = "Index", $params = null)
    {
        $url = '/' . urlencode($blog);
        $url .= '/' . urlencode($controllerName);
        $url .= '/' . urlencode($actionName);

        if ($params != null) {
            $encodedParams = array_map($params, 'urlencode');
            $url .= implode('/', $encodedParams);
        }

        $this->redirectToUrl($url);
    }

    protected function getControllerName()
    {
        $fullClassName = substr(get_class($this), 12);
        $classNameLength = strlen($fullClassName) - strlen('Controller');
        return substr($fullClassName, 0, $classNameLength);
    }

    public function isLoggedIn()
    {
        if ( isset( $_SESSION['username'] ) ) {
       // if (!empty($this->session->username)) {
            return true;
        }

        return false;
    }

    public function getUsername()
    {
        if ($this->isLoggedIn()) {
             return $_SESSION['username'];
            //return $this->session->username;
        }

        return null;
    }

    public function isOwnerOfBlog(){
        return $this->modelData->isOwnerOfBlog($this->blogName, $this->getUsername());
    }

    function addInfoMessage($msg)
    {
        $this->addMessage($msg, 'alert-success');
    }

    function addErrorMessage($msg)
    {
        $this->addMessage($msg, 'alert-danger');
    }

    function addMessage($msg, $type)
    {
//        if (empty($this->session->messages)) {
//            $this->session->messages = array();
//        };
//
//        $arr = $this->session->messages;
//        array_push($arr, array('text' => $msg, 'type' => $type));
//        $this->session->messages = $arr;
        //array_push($this->session->messages, array('text' => $msg, 'type' => $type));
        if (!isset($_SESSION['messages'])) {
            $_SESSION['messages'] = array();
        };
        array_push($_SESSION['messages'],
            array('text' => $msg, 'type' => $type));
    }

    public function authorize()
    {
        if (!$this->isLoggedIn()) {
            $this->addErrorMessage("Please login first");
            $this->redirect(SYSTEM_BLOG, "users", "login");
        }
    }
}