<?php
require_once 'app/bootstrap.php';

$request = new \LifeRaft\Core\Request();
$app = new \LifeRaft\Core\App( request: $request, config: $config );
$app->run();

?>