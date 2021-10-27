<?php

namespace LifeRaft\Database;

use LifeRaft\Core\Attributes\Injectable;
use LifeRaft\Core\Config;
use LifeRaft\Core\Responses\BadRequestErrorResponse;
use LifeRaft\Core\Responses\NotImplementedErrorResponse;
use LifeRaft\Database\Attributes\Repository;
use LifeRaft\Database\Interfaces\IEntity;
use LifeRaft\Database\Interfaces\IRepository;
use LifeRaft\Database\Queries\SQLQuery;
use PDO;
use ReflectionClass;
use stdClass;

#[Repository]
#[Injectable]
class BaseRepository implements IRepository
{
  protected array $readOnlyFields = ['id', 'createdAt', 'updatedAt', 'deletedAt'];
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
      $instance             = $attribute->newInstance();
      $this->entity         = $instance->entity;
      $this->databaseType   = $instance->databaseType;
      $this->databaseName   = $instance->databaseName;
      $this->tableName      = $instance->tableName;
      $this->fetchMode      = $instance->fetchMode;
      $this->dbContext      = $instance->dbContext;
      $this->readOnlyFields = $instance->readOnlyFields;
    }

    if (is_null($this->dbContext) || empty($this->dbContext))
    {
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

    $this->query = new SQLQuery( db: $this->dbContext(), fetchClass: $this->entity, fetchMode: $this->fetchMode );
  }

  public function dbContext(): ?PDO
  {
    return $this->dbContext;
  }

  public function entityName(): string
  {
    return $this->entity;
  }

  public function commit(): bool
  {
    exit(new NotImplementedErrorResponse(message: 'Commit message not implemented.'));
    return false;
  }

  public function find(string $conditions): array
  {
    $entities = [];

    try
    {
      $this->query = new SQLQuery(
        db: $this->dbContext(),
        fetchClass: $this->entity,
        fetchMode: $this->fetchMode
      );

      $result = $this->query->select()->all()->from(tableReferences: $this->tableName)->where(condition: 'deleted_at IS NOT NULL')->execute();
      if ($result->isOK())
      {
        $entities = $result->value();
      }
      else
      {
        exit(new BadRequestErrorResponse(message: strval($result)));
      }
    }
    catch(\Exception $e)
    {
      exit(new BadRequestErrorResponse(message: $e->getMessage()));
    }

    return $entities;
  }

  public function findOne(int $id): null|IEntity|stdClass
  {
    $result =
      $this->query
        ->select()
        ->all(columns: $this->entity::columns(exclude: ['password']))
        ->from(tableReferences: $this->tableName)
        ->where("id=$id")
        ->and(condition: "(deleted_at='1000-01-01 00:00:00' OR deleted_at=NULL)")
        ->execute();

    if ($result->isOK())
    {
      if (!empty($result->value()))
      {
        $entities = $result->value();
        return array_pop($entities);
      }
    }
    else
    {
      exit(new BadRequestErrorResponse(message: strval($result)));
    }

    return null;
  }

  public function findAll(?int $limit = null, ?int $skip = null ): array
  {
    $limit  = is_null($limit) ? Config::get('request')['DEFAULT_LIMIT'] : $limit;
    $skip   = is_null($skip)  ? Config::get('request')['DEFAULT_SKIP']  : $skip;

    $result =
      $this->query
        ->select()
        ->all(columns: $this->entity::columns(exclude: ['password']))
        ->from(tableReferences: $this->tableName)
        ->where(condition: "deleted_at='1000-01-01 00:00:00'")
        ->or(condition: 'deleted_at=NULL')
        ->limit( limit: $limit, offset: $skip)
        ->execute();

    if ($result->isError())
    {
      exit(new BadRequestErrorResponse(message: $result->toJSON()));
    }

    return $result->value();
  }

  public function add(IEntity $entity): IEntity|stdClass|false
  {
    $columns = $entity->columns(exclude: $this->readOnlyFields);
    $values = $entity->values(exclude: $this->readOnlyFields);

    $result = $this->query->insertInto(tableName: $this->tableName)->singleRow(columns: $columns)->values( valuesList: $values )->execute();

    if ($result->isOK())
    {
      return $this->findOne(id: $this->query->lastInsertId());
    }

    return false;
  }

  public function addRange(array $entities): array|false
  {
    if (empty($entities))
    {
      return false;
    }

    $columns = $this->entity::columns(exclude: $this->readOnlyFields);
    $rowList =  [];

    foreach ($entities as $entity)
    {
      $obj = $this->entity::newInstanceFromObject($entity);
      $values = $obj->values(exclude: $this->readOnlyFields);
      array_push($rowList, $values);
    }

    $result = $this->query->insertInto(tableName: $this->tableName)->multipleRows(columns: $columns)->rows(rowsList: $rowList)->execute();

    if ($result->isError())
    {
      if (Config::environment('ENVIORNMENT') === 'DEV' && Config::environment('DEBUG') === TRUE)
      {
        exit(new BadRequestErrorResponse(message: $result->toJSON()));
      }

      return false;
    }

    return $entities;
  }

  public function update(IEntity $entity): IEntity|stdClass|false
  {
    $id = $entity->id;
    $result = $this->query->update(tableName: $this->tableName)->set(assignmentList: $entity->columnValuePairs(exclude: $this->readOnlyFields))->where("id=${id}")->execute();

    if ($result->isOK())
    {
      return $this->findOne(id: $entity->id);
    }

    return false;
  }

  public function remove(IEntity $entity): IEntity|stdClass|false
  {
    $id = $entity->id;
    $now = date(DATE_ATOM);
    $result =
      $this->query
        ->update(tableName: $this->tableName)
        ->set(assignmentList: ['deleted_at' => $now])
        ->where("id=${id}")
        ->execute();

    if ($result->isOK())
    {
      return $entity;
    }

    return false;
  }

  public function removeRange(array $entities): array|false
  {
    return [];
  }
}

?>