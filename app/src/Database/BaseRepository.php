<?php

namespace LifeRaft\Database;

use LifeRaft\Core\Attributes\Injectable;
use LifeRaft\Database\Attributes\Repository;
use LifeRaft\Database\Interfaces\IEntity;
use LifeRaft\Database\Interfaces\IRepository;
use LifeRaft\Database\Queries\SQLQuery;
use PDO;
use ReflectionClass;

#[Repository]
#[Injectable]
class BaseRepository implements IRepository
{
  protected ?PDO $dbContext = null;
  protected ?SQLQuery $query = null;
  protected string|IEntity $entity = '';
  protected string $databaseType = '';
  protected string $databaseName = '';
  protected string $tableName = '';
  protected int $fetchMode = \PDO::FETCH_CLASS;

  public function __construct() {
    # Get db connection
    $reflection = new ReflectionClass(objectOrClass: $this);
    $attributes = $reflection->getAttributes(Repository::class);

    foreach ($attributes as $attribute)
    {
      $instance = $attribute->newInstance();
      $this->entity = $instance->entity;
      $this->databaseType = $instance->databaseType;
      $this->databaseName = $instance->databaseName;
      $this->tableName = $instance->tableName;
      $this->fetchMode = $instance->fetchMode;
    }

    $this->dbContext = match ($instance->databaseType) {
      'mysql'       => DBFactory::getMySQLConnection(dbName: $instance->databaseName),
      'mariadb'     => DBFactory::getMariaDBConnection(dbName: $instance->databaseName),
      'pgsql'       => DBFactory::getPostgreSQLConnection(dbName: $instance->databaseName),
      'postgresql'  => DBFactory::getPostgreSQLConnection(dbName: $instance->databaseName),
      'sqlite'      => DBFactory::getSQLiteConnection(dbName: $instance->databaseName),
      'mongodb'     => DBFactory::getMongoDbConnection(dbName: $instance->databaseName),
      default       => DBFactory::getMariaDBConnection(dbName: $instance->databaseName)
    };
  }

  public function dbContext(): ?PDO
  {
    return $this->dbContext;
  }

  public function find(IEntity $entity): array
  {
    return [];
  }

  public function get(int $id): IEntity|false
  {
    $query = new SQLQuery( db: $this->dbContext(), fetchClass: $this->entity, fetchMode: $this->fetchMode );
    // $result = $query->select()->all()

    return new BaseEntity();
  }

  public function getAll(): array
  {
    return [];
  }

  public function add(IEntity $obj): void
  {
  }

  public function addRange(IEntity $entity): void
  {
  }

  public function remove(IEntity $obj): void
  {
  }

  public function removeRange(array $entities): void
  {
  }
}

?>