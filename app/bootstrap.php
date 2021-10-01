<?php

$config_file_name = 'default';
$GLOBALS['config'] = require_once("config/{$config_file_name}.php");

# Load utilitiy functions
require_once 'src/Util/index.php';

spl_autoload_register(function ($class) {
  $namespaces = explode('\\', $class);
  if ($namespaces[0] == 'LifeRaft')
  {
    $namespaces[0] = 'src';
  }
  $classpath = implode('/', $namespaces) . '.php';

  if (!file_exists("app/$classpath"))
  {
    error_log("Unknown file: $classpath", 0);
    http_response_code(500);
    exit(1);
  }

  require_once $classpath;
});

?>