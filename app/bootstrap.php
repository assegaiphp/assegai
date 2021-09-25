<?php

$config_file_name = 'default';
$GLOBALS['config'] = require_once("config/{$config_file_name}.php");

spl_autoload_register(function ($class) {
  $namespaces = explode('\\', $class);
  if ($namespaces[0] == 'LifeRaft')
  {
    $namespaces[0] = 'src';
  }
  $classpath = implode('/', $namespaces) . '.php';

  require_once $classpath;
});

?>