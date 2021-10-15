<?php
require_once 'src/Core/Config.php';

use LifeRaft\Core\Config;

Config::hydrate();

$GLOBALS['config'] = require_once("config/default.php");

if (Config::environment('ENVIORNMENT') === 'PROD' && file_exists('app/config/production.php'))
{
  $prod_config = require_once("config/production.php");
  if (is_array($prod_config))
  {
    $GLOBALS['config'] = array_merge($GLOBALS['config'], $prod_config);
  }
}

if (file_exists('app/config/local.php'))
{
  $local_config = require_once("config/local.php");
  if (is_array($local_config))
  {
    $GLOBALS['config'] = array_merge($GLOBALS['config'], $local_config);
  }
}

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