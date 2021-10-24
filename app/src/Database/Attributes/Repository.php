<?php

namespace LifeRaft\Database\Attributes;

use Attribute;
use LifeRaft\Database\BaseEntity;
use LifeRaft\Database\DBFactory;

#[Attribute]
class Repository
{
  public ?\PDO $db = null;

  public function __construct(
    public string $entity = BaseEntity::class,
    public string $databaseType = 'mariadb',
    public string $databaseName = 'assegai_test',
    public string $tableName = '',
    public int $fetchMode = \PDO::FETCH_CLASS,
  )
  {
    if (!empty($databaseType) && !empty($databaseName))
    {
      $this->db = match($databaseType) {
        'mariadb' => DBFactory::getMariaDBConnection(dbName: $databaseName),
        'mysql' => DBFactory::getMySQLConnection(dbName: $databaseName),
        'postgresql' => DBFactory::getPostgreSQLConnection(dbName: $databaseName),
        'sqlite' => DBFactory::getSQLiteConnection(dbName: $databaseName),
        'mongodb' => DBFactory::getMongoDbConnection(dbName: $databaseName),
        default => DBFactory::getMariaDBConnection(dbName: $databaseName)
      };
    }
  }
}

?>