<?php
require_once 'src/Core/Config.php';

use LifeRaft\Core\Config;

Config::hydrate();

$GLOBALS['config'] = require("config/default.php");

if (Config::environment('ENVIRONMENT') === 'PROD' && file_exists('app/config/production.php'))
{
  $prodConfig = require("config/production.php");
  if (is_array($prodConfig))
  {
    $GLOBALS['config'] = array_merge($GLOBALS['config'], $prodConfig);
  }
}

if (file_exists('app/config/local.php'))
{
  $localConfig = require("config/local.php");
  if (is_array($localConfig))
  {
    $GLOBALS['config'] = array_merge($GLOBALS['config'], $localConfig);
  }
}

?>