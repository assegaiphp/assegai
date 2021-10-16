<?php

namespace LifeRaft\Database;

use LifeRaft\Core\Config;
use LifeRaft\Core\Responses\InternalServerErrorResponse;
use PDO;

/**
 * The `DBFactory` class houses static methods for creating **Database 
 * connection objects**.
 */
final class DBFactory
{
  public static function getMySQLConnection(string $dbName): PDO
  {
    $config = Config::get('databases')['mysql'][$dbName];

    try
    {
      extract($config);
      return new PDO(
        dsn: "mysql:host=$host;port=$port;dbname=$name",
        username: $user,
        password: $password
      );
    }
    catch (\Exception $e)
    {
      $error_message = strval(new InternalServerErrorResponse());

      if (Config::environment('ENVIORNMENT') === 'DEV' && Config::environment('DEBUG') === 'TRUE')
      {
        $error_message .= "\n" . $e->getMessage();
      }

      die($error_message);
    }
  }

  public static function getMariaDBConnection(string $dbName): PDO
  {
    $config = Config::get('databases')['mariadb'][$dbName];

    try
    {
      extract($config);
      return new PDO(
        dsn: "mysql:host=$host;port=$port;dbname=$name",
        username: $user,
        password: $password
      );
    }
    catch (\Exception $e)
    {
      $error_message = strval(new InternalServerErrorResponse());

      if (Config::environment('ENVIRONMENT') === 'DEV' && Config::environment('DEBUG') === 'TRUE')
      {
        $error_message .= "\n" . $e->getMessage();
      }

      die($error_message);
    }
  }

  public static function getPostgreSQLConnection(string $dbName): PDO
  {
    $config = Config::get('databases')['pgsql'][$dbName];

    try
    {
      extract($config);
      return new PDO(
        dsn: "pgsql:host=$host;port=$port;dbname=$name",
        username: $user,
        password: $password
      );
    }
    catch (\Exception $e)
    {
      $error_message = strval(new InternalServerErrorResponse());

      if (Config::environment('ENVIRONMENT') === 'DEV' && Config::environment('DEBUG') === 'TRUE')
      {
        $error_message .= "\n" . $e->getMessage();
      }

      die($error_message);
    }
  }

  public static function getSQLiteConnection(string $dbName): PDO
  {
    $config = Config::get('databases')['sqlite'][$dbName];

    try
    {
      extract($config);
      return new PDO( dsn: "sqlite:$path" );
    }
    catch (\Exception $e)
    {
      $error_message = strval(new InternalServerErrorResponse());

      if (Config::environment('ENVIRONMENT') === 'DEV' && Config::environment('DEBUG') === 'TRUE')
      {
        $error_message .= "\n" . $e->getMessage();
      }

      die($error_message);
    }
  }

  public static function getMongoDbConnection(string $dbName): PDO
  {
    $config = Config::get('databases')['mongodb'][$dbName];

    try
    {

    }
    catch (\Exception $e)
    {
      $error_message = strval(new InternalServerErrorResponse());

      if (Config::environment('ENVIRONMENT') === 'DEV' && Config::environment('DEBUG') === 'TRUE')
      {
        $error_message .= "\n" . $e->getMessage();
      }

      die($error_message);
    }
  }
}

?>