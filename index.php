<?php

include 'MVC/App.php';
mb_internal_encoding("UTF-8");
mb_http_output( "UTF-8" );
$app = \MVC\App::getInstance();
$app->run();

