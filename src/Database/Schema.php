<?php

namespace Assegai\Database;

use Assegai\Core\Exceptions\GeneralSQLQueryException;
use Assegai\Database\Interfaces\ISchema;
use Assegai\Database\Management\EntityManager;
use Assegai\Database\Types\SQLDialect;
use Exception;
use PDO;
use PDOStatement;
use ReflectionClass;

/**
 * The `Schema` class provides a database agnostic way of manipulating tables. 
 * It works well with all the databases supported by 
 * [Assegai](https://assegai.io/docs/supported-databases), and has a unified 
 * API across all of these systems.
 */
class Schema implements ISchema
{
  public static function create(string $entityClass, ?SchemaOptions $options = null): ?bool
  {
    if (is_null($options))
    {
      $options = new SchemaOptions();
    }

    EntityManager::validateEntityName(entityClass: $entityClass);

    try 
    {
      $reflection = new ReflectionClass(objectOrClass: $entityClass);
      $instance = $reflection->newInstance();
      $db = DBFactory::getSQLConnection(dbName: $options->dbName(), dialect: $options->dialect());

      if ($options->dropSchema)
      {
        self::dropIfExists(entityClass: $entityClass, options: $options);
      }

      $query = $instance->schema(dialect: $options->dialect());
      exit($query . PHP_EOL);
      $statement = $db->prepare(query: $query);

      return $statement->execute();
    }
    catch(Exception $e)
    {
      exit($e->getMessage());
    }
  }

  public static function createIfNotExists(string $entityClass, ?SchemaOptions $options = null): ?bool
  {
    if (is_null($options))
    {
      $options = new SchemaOptions();
    }

    try 
    {
      $reflection = new ReflectionClass(objectOrClass: $entityClass);
      $instance = $reflection->newInstance();
      $query = $instance->schema(dialect: $options->dialect());

      $db = DBFactory::getSQLConnection(dbName: $options->dbName(), dialect: $options->dialect());
      $statement = $db->prepare(query: $query);

      return $statement->execute();
    }
    catch(Exception $e)
    {
      exit($e->getMessage());
    }
  }

  public static function rename(string $from, string $to): ?bool
  {
    return false;
  }

  public static function alter(string $entityClass): ?bool
  {
    return false;
  }

  public static function info(string $entityClass): ?string
  {
    return null;
  }

  public static function truncate(string $entityClass): ?bool
  {
    return false;
  }

  public static function drop(string $entityClass, ?SchemaOptions $options = null): ?bool
  {
    if (is_null($options))
    {
      $options = new SchemaOptions();
    }

    try 
    {
      $reflection = new ReflectionClass(objectOrClass: $entityClass);
      $instance = $reflection->newInstance();
      $tableName = $instance->tableName();
      $query = "DROP TABLE `$tableName`";

      $db = DBFactory::getSQLConnection(dbName: $options->dbName(), dialect: $options->dialect());
      $statement = $db->prepare(query: $query);

      return $statement->execute();
    }
    catch(Exception $e)
    {
      exit($e->getMessage());
    }
  }

  public static function dropIfExists(string $entityClass, ?SchemaOptions $options = null): ?bool
  {
    if (is_null($options))
    {
      $options = new SchemaOptions();
    }

    try 
    {
      $reflection = new ReflectionClass(objectOrClass: $entityClass);
      $instance = $reflection->newInstance();
      $tableName = $instance->tableName();
      $query = "DROP TABLE";

      switch ($options->dialect())
      {
        case SQLDialect::MARIADB->value:
        case SQLDialect::MYSQL->value:
        case SQLDialect::POSTGRESSQL->value:
          default:
          $query .= ' IF EXISTS';
          break;
      }
      $query .= " `$tableName`";

      $db = DBFactory::getSQLConnection(dbName: $options->dbName(), dialect: $options->dialect());
      $statement = $db->prepare(query: $query);

      return $statement->execute();
    }
    catch(Exception $e)
    {
      exit($e->getMessage());
    }
  }

  public static function dbExists(PDO|DataSource $dataSource, string $databaseName): bool
  {
    $query = "SHOW DATABASES LIKE '$databaseName'";
    $exists = false;
    $executionResult = ($dataSource instanceof DataSource)
      ? $dataSource->manager->query(query: $query)
      : $dataSource->query($query);

    if ($executionResult === false)
    {
      throw new GeneralSQLQueryException(message: json_encode($dataSource->errorInfo()));
    }

    $result = $executionResult->fetchAll(PDO::FETCH_ASSOC);
    
    $exists = !empty($result);

    return $exists;
  }

  public static function dbTableExists(PDO|DataSource $dataSource, string $databaseName, string $tableName, string $dialect = 'mysql'): bool
  {
    $query = "SHOW TABLES LIKE '$tableName'";

    $exists = false;
    $executionResult = ($dataSource instanceof DataSource)
      ? $dataSource->manager->query(query: $query)
      : $dataSource->query($query);

    if ($executionResult === false)
    {
      throw new GeneralSQLQueryException(message: json_encode($dataSource->errorInfo()));
    }

    $result = $executionResult->fetchAll(PDO::FETCH_ASSOC);
    
    $exists = !empty($result);

    return $exists;
  }
}

