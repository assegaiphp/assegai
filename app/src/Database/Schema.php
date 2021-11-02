<?php

namespace LifeRaft\Database;

use LifeRaft\Database\Interfaces\ISchema;
use ReflectionClass;

/**
 * The `Schema` class provides a database agnostic way of manipulating tables. It works well with all of the databases supported by [Assegai](https://assegai.io/docs/supported-databases), and has a unified API across all of these systems.
 */
class Schema implements ISchema
{
  public static function create(string $entityClass, ?SchemaOptions $options = null): ?bool
  {
    if (is_null($options))
    {
      $options = new SchemaOptions();
    }

    $reflection = new ReflectionClass(objectOrClass: $entityClass);
    $instance = $reflection->newInstance();
    $query = $instance->schema(dialect: $options->dialect());
    
    try 
    {
      $db = DBFactory::getSQLConnection(dbName: $options->dbName(), dialect: $options->dialect()); 
      $statement = $db->prepare(query: $query);

      return $statement->execute();
    }
    catch(\Exception $e)
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

    $reflection = new ReflectionClass(objectOrClass: $entityClass);
    $instance = $reflection->newInstance();
    $query = $instance->schema(dialect: $options->dialect());
    
    try 
    {
      $db = DBFactory::getSQLConnection(dbName: $options->dbName(), dialect: $options->dialect()); 
      $statement = $db->prepare(query: $query);

      return $statement->execute();
    }
    catch(\Exception $e)
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

    $reflection = new ReflectionClass(objectOrClass: $entityClass);
    $instance = $reflection->newInstance();
    $tableName = $instance->tableName();
    $query = "DROP TABLE `${tableName}`";
    
    try 
    {
      $db = DBFactory::getSQLConnection(dbName: $options->dbName(), dialect: $options->dialect()); 
      $statement = $db->prepare(query: $query);

      return $statement->execute();
    }
    catch(\Exception $e)
    {
      exit($e->getMessage());
    }
  }

  public static function dropIfExists(string $entityClass): ?bool
  {
    return false;
  }

}

?>