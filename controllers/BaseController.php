<?php
namespace CONTROLLERS;
abstract class BaseController
{
    protected $controllerName = DEFAULT_CONTROLLER;
    protected $actionName = DEFAULT_ACTION;
    protected $modelData = null;
    protected $title;
    protected $isPost = false;
    protected $session = null;
    protected $blogName = null;
    protected $pageSize = 3;

    public function __construct($blog_name)
    {
        $this->blogName = $blog_name;
        $this->controllerName = $this->getControllerName();
        $model_file = '\\MODELS\\' . $this->controllerName . 'Model';
        $pre_title = $this->blogName != null ? $this->blogName . ' - ' : '';
        $this->title = $pre_title . $this->controllerName;
        $this->modelData = new $model_file();
        session_set_cookie_params(1800, "/");
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->isPost = true;
        } else {
            $this->isPost = false;
        }

        $this->checkBlogExists();
    }

    public function renderView($action = null, $include_layout = true)
    {
        $selected_action = $action == null ? strtolower($this->actionName) : strtolower($action);
        $view_file_name = 'views/' . strtolower($this->controllerName) . '/' . $selected_action . '.php';
        if ($include_layout) {
            $header_file = 'views/layouts/header.php';
            include_once($header_file);
        }

        include_once($view_file_name);
        if ($include_layout) {
            $footer_file = 'views/layouts/footer.php';
            include_once($footer_file);
        }

        //$this->isViewRendered = true;
    }

    public function redirectToUrl($url)
    {
        header("Location: " . $url);
        die;
    }

    public function redirect($blog, $controller_name, $action_name = DEFAULT_ACTION, $params = null)
    {
        $url = '/' . urlencode($blog);
        $url .= '/' . urlencode($controller_name);
        $url .= '/' . urlencode($action_name);

        if ($params != null) {
            $encodedParams = array_map( 'urlencode', $params);
            $url .= '/' . implode('/', $encodedParams);
        }

        $this->redirectToUrl($url);
    }

    protected function getControllerName()
    {
        $full_class_name = substr(get_class($this), 12);
        $class_name_length = strlen($full_class_name) - strlen('Controller');
        return substr($full_class_name, 0, $class_name_length);
    }

    public function isLoggedIn()
    {
        if (isset($_SESSION['username'])) {
            return true;
        }

        return false;
    }

    public function getUsername()
    {
        if ($this->isLoggedIn()) {
            return $_SESSION['username'];
        }

        return null;
    }

    public function isOwnerOfBlog()
    {
        if ($this->getUsername() == 'admin' || $this->modelData->isOwnerOfBlog($this->blogName, $this->getUsername())){
            return true;
        }

        return false;
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
        if (!isset($_SESSION['messages'])) {
            $_SESSION['messages'] = array();
        };

        array_push($_SESSION['messages'], array('text' => $msg, 'type' => $type));
    }

    public function authorize()
    {
        if (!$this->isLoggedIn()) {
            $this->addErrorMessage("Please login first");
            $this->redirect(SYSTEM_BLOG, "users", "login");
        }
    }

    private function checkBlogExists()
    {
        if ($this->blogName != SYSTEM_BLOG && $this->blogName != ADMIN_BLOG && $this->blogName !=null) {
            if (!$this->modelData->isBlogExists($this->blogName)) {
                $this->addErrorMessage("No such blog exists");
                $this->redirect(SYSTEM_BLOG, DEFAULT_CONTROLLER, DEFAULT_ACTION);
            }
        }
    }
}