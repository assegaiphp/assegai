<?php

use Assegai\Core\App;
use Assegai\Core\Injection\Inyeleti;
use Assegai\Core\Request;
use Assegai\Core\Routing\Router;

require_once 'app/bootstrap.php';

$request = new Request();
$injector = new Inyeleti();
$router = new Router(injector: $injector);
$app = new App( request: $request, router: $router, config: $config );
$app->run();
