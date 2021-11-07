<?php

use LifeRaft\Core\App;
use LifeRaft\Core\Request;
use LifeRaft\Core\Routing\Router;

require_once 'app/bootstrap.php';

$request = new Request();
$router = new Router();
$app = new App( request: $request, router: $router, config: $config );
$app->run();
