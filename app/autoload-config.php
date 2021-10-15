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

?>