<?php
namespace MVC;


class FrontController
{

    private static $instance = null;
    public $components = array();
    public $controller = null;
    public $action = null;
    public $params = array();
    //public $admin_routing = false;
    // public $param = array();


    private function __construct()
    {
    }

    public function dispatch()
    {
        include 'config/app.php';
        $router = new \MVC\Router();
        $router->parse();

        $controller = $router->getController() ;
        if (isset($controller) && file_exists('controllers/' . $controller . 'Controller.php')) {
//            $admin_folder = $admin_routing ? 'admin/' : '';
//            include_once 'controllers/' . $admin_folder . $controller . '.php';
            // Is admin controller?
//            $admin_namespace = $admin_routing ? '\Admin' : '';
            $controller = '\\CONTROLLERS\\' . $controller. 'Controller';
            $instance = new $controller();
            $action = $router->getAction();
            $params = $router->getParams();

            // Call the object and the method
            if (isset($action) && method_exists($instance, $action)) {
                call_user_func_array(array($instance, $action), array($params));
            } else {
                // fallback to default action of that controller
                call_user_func_array(array($instance, DEFAULT_ACTION), array());
            }
        } else {
            // fallback to default controller and action - set in app.php config file
            $defaultController = '\\CONTROLLERS\\' . DEFAULT_CONTROLLER . 'Controller';
            $instance = new $defaultController();
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