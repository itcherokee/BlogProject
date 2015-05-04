<?php

include 'MVC/App.php';

$app = \MVC\App::getInstance();

// Register namespaces
\MVC\Loader::registerNamespace('CONTROLLERS', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR);
\MVC\Loader::registerNamespace('MODELS', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR);

$app->run();

