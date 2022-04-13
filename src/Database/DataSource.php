<?php

namespace Assegai\Database;

use Assegai\Core\Exceptions\ClassNotFoundException;
use Assegai\Database\Interfaces\IRepository;
use Assegai\Database\Management\EntityManager;
use Assegai\Database\Types\DataSourceType;
use PDO;

class DataSource
{
  public readonly EntityManager $manager;
  public readonly PDO $db;
  public readonly string $type;

  public function __construct(DataSourceOptions $options)
  {
    $this->type = $options->type;
    // TODO: #80 Check if the specified databases is in config @amasiye
    if (
      !empty($options->database) &&
      !empty($options->username) &&
      !empty($options->password) &&
      !empty($options->getPort())
    )
    {
      $host = $options->getHost();
      $name = $options->database;
      $port = $options->getPort();

      $dsn = match ($this->type) {
        DataSourceType::MYSQL,
        DataSourceType::MYSQL => "mysql:host=$host;port=$port;dbname=$name",
        DataSourceType::POSTGRESQL => "pgsql:host=$host;port=$port;dbname=$name",
        DataSourceType::MSSQL => "sqlsrv:Server=$host,port;Database=$name",
        DataSourceType::SQLITE => "sqlite:$name",
        default => "mysql:host=$host;port=$port;dbname=$name"
      };

      $this->db = new PDO(dsn: $dsn, username: $options->username, password: $options->password);
    }
    else
    {
      $this->db = match ($this->type) {
        DataSourceType::POSTGRESQL  => DBFactory::getPostgreSQLConnection(dbName: $options->database),
        DataSourceType::SQLITE      => DBFactory::getSQLiteConnection(dbName: $options->database),
        DataSourceType::MONGODB     => DBFactory::getMongoDbConnection(dbName: $options->database),
        DataSourceType::MARIADB,
        DataSourceType::MYSQL       => DBFactory::getMySQLConnection(dbName: $options->database),
        default                     => DBFactory::getSQLConnection(dbName: $options->database, dialect: $options->type->value)
      };
    }

    $this->manager = new EntityManager(connection: $this);
  }

  /**
   * @param string $entityName The target entity for the repository 
   * @throws ClassNotFoundException
   */
  public function getRepository(string $entityName): IRepository
  {
    if (!class_exists($entityName))
    {
      throw new ClassNotFoundException(className: $entityName);
    }

    return new CustomRepository(entity: $entityName, connection: $this);
  }
}
