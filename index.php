<?php

use Assegai\Core\App;
use Assegai\Core\Request;
use Assegai\Core\Routing\Router;

require_once 'app/bootstrap.php';

$request = new Request();
$router = new Router();
$app = new App( request: $request, router: $router, config: $config );
$app->run();
