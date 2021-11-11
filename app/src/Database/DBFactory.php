<?php

namespace Assegai\Database;

use Assegai\Core\Config;
use Assegai\Core\Responses\InternalServerErrorResponse;
use PDO;

/**
 * The `DBFactory` class houses static methods for creating **Database 
 * connection objects**.
 */
final class DBFactory
{
  private static array $connections = [
    'mysql' => [],
    'mariadb' => [],
    'pgsql' => [],
    'sqlite' => [],
    'mongodb' => [],
  ];

  public static function getSQLConnection(string $dbName, ?string $dialect = 'mysql'): PDO {
    return match ($dialect) {
      'mysql'       => DBFactory::getMySQLConnection(dbName: $dbName),
      'mariadb'     => DBFactory::getMariaDBConnection(dbName: $dbName),
      'pgsql'       => DBFactory::getPostgreSQLConnection(dbName: $dbName),
      'postgresql'  => DBFactory::getPostgreSQLConnection(dbName: $dbName),
      'sqlite'      => DBFactory::getSQLiteConnection(dbName: $dbName),
      default       => DBFactory::getMySQLConnection(dbName: $dbName)
    };
  }

  public static function getMySQLConnection(string $dbName): PDO
  {
    $type = 'mysql';
    if (!isset(DBFactory::$connections[$type][$dbName]) || empty(DBFactory::$connections[$type][$dbName]))
    {
      $config = Config::get('databases')[$type][$dbName];
  
      try
      {
        extract($config);
        DBFactory::$connections[$type][$dbName] = new PDO(
          dsn: "mysql:host=$host;port=$port;dbname=$name",
          username: $user,
          password: $password
        );
      }
      catch (\Exception $e)
      {
        $errorMessage = strval(new InternalServerErrorResponse());
  
        if (Config::environment('ENVIRONMENT') === 'DEV' && Config::environment('DEBUG') === 'TRUE')
        {
          $errorMessage .= "\n" . $e->getMessage();
        }
  
        die($errorMessage);
      }
    }

    return DBFactory::$connections[$type][$dbName];
  }

  public static function getMariaDBConnection(string $dbName): PDO
  {
    $type = 'mariadb';
    if (!isset(DBFactory::$connections[$type][$dbName]) || empty(DBFactory::$connections[$type][$dbName]))
    {
      $config = Config::get('databases')[$type][$dbName];
  
      try
      {
        extract($config);
        DBFactory::$connections[$type][$dbName] = new PDO(
          dsn: "mysql:host=$host;port=$port;dbname=$name",
          username: $user,
          password: $password
        );
      }
      catch (\Exception $e)
      {
        $errorMessage = strval(new InternalServerErrorResponse());
  
        if (Config::environment('ENVIRONMENT') === 'DEV' && Config::environment('DEBUG') === 'TRUE')
        {
          $errorMessage .= "\n" . $e->getMessage();
        }
  
        die($errorMessage);
      }
    }

    return DBFactory::$connections[$type][$dbName];
  }

  public static function getPostgreSQLConnection(string $dbName): PDO
  {
    $type = 'pgsql';
    if (!isset(DBFactory::$connections[$type][$dbName]) || empty(DBFactory::$connections[$type][$dbName]))
    {
      $config = Config::get('databases')[$type][$dbName];
  
      try
      {
        extract($config);
        DBFactory::$connections[$type][$dbName] = new PDO(
          dsn: "pgsql:host=$host;port=$port;dbname=$name",
          username: $user,
          password: $password
        );
      }
      catch (\Exception $e)
      {
        $errorMessage = strval(new InternalServerErrorResponse());
  
        if (Config::environment('ENVIRONMENT') === 'DEV' && Config::environment('DEBUG') === 'TRUE')
        {
          $errorMessage .= "\n" . $e->getMessage();
        }
  
        die($errorMessage);
      }
    }

    return DBFactory::$connections[$type][$dbName];
  }

  public static function getSQLiteConnection(string $dbName): PDO
  {
    $type = 'sqlite';
    if (!isset(DBFactory::$connections[$type][$dbName]) || empty(DBFactory::$connections[$type][$dbName]))
    {
      $config = Config::get('databases')[$type][$dbName];
  
      try
      {
        extract($config);
        DBFactory::$connections[$type][$dbName] = new PDO( dsn: "sqlite:$path" );
      }
      catch (\Exception $e)
      {
        $errorMessage = strval(new InternalServerErrorResponse());
  
        if (Config::environment('ENVIRONMENT') === 'DEV' && Config::environment('DEBUG') === 'TRUE')
        {
          $errorMessage .= "\n" . $e->getMessage();
        }
  
        die($errorMessage);
      }
    }

    return DBFactory::$connections[$type][$dbName];
  }

  public static function getMongoDbConnection(string $dbName): PDO
  {
    $type = 'mongodb';
    if (!isset(DBFactory::$connections[$type][$dbName]) || empty(DBFactory::$connections[$type][$dbName]))
    {
      $config = Config::get('databases')[$type][$dbName];
  
      try
      {
  
      }
      catch (\Exception $e)
      {
        $errorMessage = strval(new InternalServerErrorResponse());
  
        if (Config::environment('ENVIRONMENT') === 'DEV' && Config::environment('DEBUG') === 'TRUE')
        {
          $errorMessage .= "\n" . $e->getMessage();
        }
  
        die($errorMessage);
      }
    }

    return DBFactory::$connections[$type][$dbName];
  }
}

?>