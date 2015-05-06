<?php
namespace MVC;
require_once 'Loader.php';

class App
{
    private static $instance = null;
    private $frontController = null;
    private $database = null;
    private $session = null;

    private function __construct()
    {
        \MVC\Loader::registerNamespace('MVC', dirname(__FILE__) . DIRECTORY_SEPARATOR);
        \MVC\Loader::registerAutoload();

        // Register Models & COntrollers namespaces
        \MVC\Loader::registerNamespace('CONTROLLERS', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR );
        \MVC\Loader::registerNamespace('MODELS', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR);
    }

    /**
     * @return \MVC\App
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new \MVC\App();
        }

        return self::$instance;
    }

    public function run()
    {
        $this->frontController = \MVC\FrontController::getInstance();
      //  $this->session = new \MVC\Session('blog');
        $this->frontController->dispatch();
        $this->database = \MVC\Database::get_instance();

    }

    public function getSession(){
        return $this->session;
    }
}