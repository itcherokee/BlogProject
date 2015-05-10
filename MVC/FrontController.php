<?php
namespace MVC;

class FrontController
{
    private static $instance = null;
    public $components = array();
    public $blog = null;
    public $controller = null;
    public $action = null;
    public $params = array();

    private function __construct()
    {
    }

    public function dispatch()
    {
        include 'config/app.php';
        $router = new \MVC\Router();
        $router->parse();

        $this->blog = $router->getBlogOwner();
        if (isset($this->blog)) {
            $this->controller = $router->getController();
            if (isset($this->controller) && file_exists('controllers/' . $this->controller . 'Controller.php')) {
                $this->controller = '\\CONTROLLERS\\' . $this->controller . 'Controller';
                $instance = new $this->controller($this->blog);
                $this->action = $router->getAction();
                $this->params = $router->getParams();

                // Call the object and the method
                if (isset($this->action) && method_exists($instance, $this->action)) {
                    call_user_func_array(array($instance, $this->action), array($this->params));
                } else {
                    // fallback to default action of that controller
                    call_user_func_array(array($instance, DEFAULT_ACTION), array());
                }
            } else {
                // forward to post controller for selected blog
                $defaultController = '\\CONTROLLERS\\PostsController';
                $instance = new $defaultController($this->blog);
                call_user_func_array(array($instance, DEFAULT_ACTION), array());
            }
        } else {
            // fallback to default controller and action - set in app.php config file
            $defaultController = '\\CONTROLLERS\\' . DEFAULT_CONTROLLER . 'Controller';
            $instance = new $defaultController(SYSTEM_BLOG);
            call_user_func_array(array($instance, DEFAULT_ACTION), array());
        }
    }

    /**
     * @return \MVC\FrontController
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new \MVC\FrontController();
        }

        return self::$instance;
    }
}