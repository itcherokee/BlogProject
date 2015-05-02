<?php

include 'MVC/App.php';

$app = \MVC\App::getInstance();
$app->run();
//new \HHTH\THTHT\Test();
print_r(\MVC\Loader::getNamespaces());