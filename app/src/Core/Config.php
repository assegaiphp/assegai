<?php

namespace LifeRaft\Core;


/**
 * The Config class provides methods for retrieving or setting configuration
 * values.
 */
class Config
{
  public static function hydrate(): void
  {
    if (file_exists('.env'))
    {
      $config = [];
      $env = file('.env');
      foreach ($env as $line)
      {
        if (str_contains($line, '='))
        {
          list($key, $value) = explode('=', $line);
          
          # Remove carriage return and/or end line character
          $key = trim($key);
  
          # Filter out blank lines
          if (!empty($key))
          {
            $commentPos = strpos($value, ';');
            $config[$key] = $commentPos !== false ? trim(substr($value, 0, $commentPos)) : trim($value);
          }
        }
      }

      $_ENV = array_merge($_ENV, $config);
    }
  }
 
  public static function get(string $name): mixed
  {
    return isset($GLOBALS['config'][$name]) ? $GLOBALS['config'][$name] : NULL;
  }

  public static function set(string $name, mixed $value): void
  {
    $GLOBALS['config'][$name] = $value;
  }

  /**
   * Gets an environment configuration value from the workspace file, `.env`.
   * 
   * @param string $name The name of configuration value to be returned.
   * 
   * @return mixed Returns the current configuration value of the given name, 
   * or `NULL` if it doesn't exist.
   */
  public static function environment(string $name): mixed
  {
    return isset($_ENV[$name]) ? $_ENV[$name] : NULL;
  }

  /**
   * Sets the value of the pair identified by $name to $value, creating a new 
   * key/value pair if none existed for $name previously in the workspace `.env` file.
   * 
   * @param string $name The name of configuration value to be set.
   * @param string $value The new value to set the configuration to.
   */
  public static function setEnvironment(string $name, string $value): void
  {
    $_ENV[$name] = $value;
  }

  /**
   * Gets an environment configuration value from the workspace file, `assegai.json`.
   * 
   * @param string $name The name of configuration value to be retrieved or set.
   * 
   * @return mixed Returns the configuration value of given name if it exists, 
   * or `NULL` if the `assegai.json` file or configuration doesn't exist.
   */
  public static function workspace(string $name): mixed
  {
    if (!file_exists('assegai.json'))
    {
      return NULL;
    }
    $config = file_get_contents('assegai.json');

    if (!empty($config) && str_starts_with($config, '{'))
    {
      $config = json_decode($config);
    }

    return isset($config->$name) ? $config->$name : NULL;
  }

  /**
   * @param string $value The new value to set the configuration to.
   * 
   */
  public static function setWworkspace(string $name, mixed $value): void
  {
    if (!file_exists('assegai.json'))
    {
      throw new \Exception('Missing workspace config file: assegai.json');
    }

    $config = file_get_contents('assegai.json');

    if (!empty($config) && str_starts_with($config, '{'))
    {
      $config = json_decode($config);
    }
    else
    {
      $config = json_decode(json_encode([]));
    }

    $config->$name = $value;
  }
}

?>