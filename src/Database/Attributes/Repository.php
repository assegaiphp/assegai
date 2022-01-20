<?php

namespace Assegai\Database\Attributes;

use Attribute;
use Assegai\Database\BaseEntity;
use Assegai\Database\DBFactory;
use ReflectionClass;

#[Attribute]
class Repository
{
  public ?\PDO $dbContext = null;

  public function __construct(
    public string $entity = BaseEntity::class,
    public string $databaseType = 'mariadb',
    public string $databaseName = '',
    public string $tableName = '',
    public int $fetchMode = \PDO::FETCH_CLASS,
    public array $readOnlyFields = ['id', 'createdAt', 'updatedAt', 'deletedAt'],
  )
  {
    if (!$databaseName)
    {
      $reflectionClass = new ReflectionClass(objectOrClass: $this->entity);
      $reflectionAttributes = $reflectionClass->getAttributes(Entity::class);
      foreach ($reflectionAttributes as $index => $attribute)
      {
        $attributeInstance = $attribute->newInstance();

        if ($attributeInstance->database)
        {
          $this->databaseName = $attributeInstance->database;
        }
      }
    }

    if (!empty($databaseType) && !empty($databaseName))
    {
      $this->dbContext = match($databaseType) {
        'mariadb'     => DBFactory::getMariaDBConnection(dbName: $databaseName),
        'mysql'       => DBFactory::getMySQLConnection(dbName: $databaseName),
        'postgresql'  => DBFactory::getPostgreSQLConnection(dbName: $databaseName),
        'sqlite'      => DBFactory::getSQLiteConnection(dbName: $databaseName),
        'mongodb'     => DBFactory::getMongoDbConnection(dbName: $databaseName),
        default       => DBFactory::getMariaDBConnection(dbName: $databaseName)
      };
    }
  }
}

?>