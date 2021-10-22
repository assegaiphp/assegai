<?php

use LifeRaft\Core\App;
use LifeRaft\Core\Request;

require_once 'app/bootstrap.php';

$request = new Request();
$app = new App( request: $request, config: $config );
$app->run();
