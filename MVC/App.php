<?php
namespace MVC;
require_once 'Loader.php';

class App
{
    private static $instance = null;
    private $frontController = null;
    private $database = null;

    private function __construct()
    {
        \MVC\Loader::registerNamespace('MVC', dirname(__FILE__) . DIRECTORY_SEPARATOR);
        \MVC\Loader::registerAutoload();
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
        // Register paths


        $this->frontController = \MVC\FrontController::getInstance();
        $this->frontController->dispatch();
        $this->database = \MVC\Database::get_instance();
    }
}