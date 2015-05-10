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
        foreach(self::$namespaces as $key=>$value){
            if (strpos($class,$key) === 0){
                $actualFile = str_replace('\\', DIRECTORY_SEPARATOR, $class);
                $actualFile =  substr_replace($actualFile, $value, 0, strlen($key)) . '.php';
                $actualFile = realpath($actualFile);
                if ($actualFile && is_readable(($actualFile))){
                    include $actualFile ;
                }
                else {
                    throw new \Exception('File cannot be loaded (' . $actualFile . ')');
                }

                break;
            }
        }
    }

    public static function registerNamespace($namespace, $path)
    {
        $namespace = trim($namespace);
        if (strlen($namespace) > 0) {
            if (!$path) {
                throw new \Exception('Invalid path');
            }

            $fixedPath = realpath($path);
            if ($fixedPath && is_dir($fixedPath) && is_readable($fixedPath)) {
                self::$namespaces[$namespace . '\\'] = $fixedPath . DIRECTORY_SEPARATOR;
            } else {
                throw new \Exception('Namespace directory read error: ' . $fixedPath);
            }
        } else {
            throw new \Exception('Invalid namespace ' . $namespace);
        }
    }

    public static function getNamespaces()
    {
        return self::$namespaces;
    }
}