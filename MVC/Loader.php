<?php

namespace MVC;

final class Loader
{
    private static $namespaces = array();

    private function __construct()
    {
        if (self::$instance == null) {
            self::$instance = new \MVC\Loader();
        }

        return self::$instance;
    }

    public static function registerAutoload()
    {
        spl_autoload_register(array('\MVC\Loader', 'autoload'));
    }

    public static function autoload($class)
    {
        self::loadClass($class);
    }

    private static function autoload($class)
    {
        
    }

    public static function registerNamespace($namespace, $path)
    {
        $namespace = trim($namespace);
        if (strlen($namespace) > 0) {
            if (!$path) {
                //TODO: fix it
                throw new \Exception('Invalid path');
            }

            $fixedPath = realpath($path);
            if ($fixedPath && is_dir($fixedPath) && is_readable($fixedPath)) {
                self::$namespaces[$namespace] = $fixedPath . DIRECTORY_SEPARATOR;
            } else {
                //TODO: fix it
                throw new \Exception('Namespace directory read error: ' . $fixedPath);
            }
        } else {
            //TODO: fix it
            throw new \Exception('Invalid namespace ' . $namespace);
        }
    }

    public static function getNamespaces()
    {
        return self::$namespaces;
    }
}