<?php

use LifeRaft\Core\Config;

require_once 'app/bootstrap.php';

$app = new \LifeRaft\Core\App( config: $config );
$app->run();

?>