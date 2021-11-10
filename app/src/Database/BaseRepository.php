<?php

namespace Assegai\Database;

use Assegai\Core\Attributes\Injectable;
use Assegai\Core\Config;
use Assegai\Core\Debugger;
use Assegai\Core\Responses\BadRequestErrorResponse;
use Assegai\Core\Responses\NotFoundErrorResponse;
use Assegai\Core\Responses\NotImplementedErrorResponse;
use Assegai\Database\Attributes\Columns\Column;
use Assegai\Database\Attributes\Repository;
use Assegai\Database\Interfaces\IEntity;
use Assegai\Database\Interfaces\IRepository;
use Assegai\Database\Queries\SQLDataTypes;
use Assegai\Database\Queries\SQLQuery;
use PDO;
use ReflectionClass;
use ReflectionProperty;
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

  public function find(?string $conditions): array
  {
    $entities = [];

    try
    {
      $this->query = new SQLQuery(
        db: $this->dbContext(),
        fetchClass: $this->entity,
        fetchMode: $this->fetchMode
      );

      $statement = 
        $this->query
          ->select()
          ->all()
          ->from(tableReferences: $this->tableName);
      
      if (!empty($conditions))
      {
        $statement = $statement->where(condition: $conditions)->and(condition: '`deleted_at` IS NOT NULL');
      }
      else
      {
        $statement = $statement->where(condition: '`deleted_at` IS NOT NULL');
      }

      if (isset($_GET['limit']))
      {
        $limit = $_GET['limit'];
        $skip = isset($_GET['skip']) ? $_GET['skip'] : 0;
        $statement->limit(limit: $limit, offset: $skip);
      }

      # TODO: Implement orderBy

      $result = $statement->execute();

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

  public function findOne(string $conditions, bool $filterDeleted = true): null|IEntity|stdClass
  {
    $statement =
      $this->query
        ->select()
        ->all(columns: $this->entity::columns(exclude: ['password']))
        ->from(tableReferences: $this->tableName)
        ->where(condition: $conditions);
    if ($filterDeleted)
    {
      $statement = $statement->and(condition: "(deleted_at='1000-01-01 00:00:00' OR deleted_at=NULL)");
    }
    $result = $statement->limit(limit: 1)->execute();

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
      return $this->findOne("id=" . $this->query->lastInsertId());
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
      if (Config::environment('ENVIRONMENT') === 'DEV' && Config::environment('DEBUG') === TRUE)
      {
        exit(new BadRequestErrorResponse(message: $result->toJSON()));
      }

      return false;
    }

    return $entities;
  }

  public function update(int|string $id, IEntity|stdClass $changes): IEntity|stdClass|false
  {
    if (!$this->entity::isValidEntity($changes))
    {
      Debugger::respond(new BadRequestErrorResponse(message: 'Invalid entity'));
      return false;
    }

    $result = $this->query->update(tableName: $this->tableName)->set(assignmentList: $changes->columnValuePairs(exclude: $this->readOnlyFields))->where("id=${id}")->execute();

    if ($result->isOK())
    {
      return $this->findOne("id=" . $id);
    }

    return false;
  }

  public function partialUpdate(stdClass $body): IEntity|stdClass|false
  {
    if (!isset($body->id))
    {
      exit(new BadRequestErrorResponse(message: 'Missing id field'));
    }
    $id = $body->id;
    $entity = $this->findOne("id=" . $id);

    if (is_null($entity))
    {
      exit(new NotFoundErrorResponse());
    }
    $assignmentList = [];

    foreach ($body as $prop => $value)
    {
      if (property_exists($this->entity, $prop))
      {
        if (in_array($prop, $this->readOnlyFields))
        {
          continue;
        }

        # Get colum attribute
        $reflectionProp = new ReflectionProperty($this->entity, $prop);
        $attributes = $reflectionProp->getAttributes();

        foreach ($attributes as $attribute)
        {
          $instance = $attribute->newInstance();
          if (is_a($instance, Column::class))
          {
            # Get column name and pair with $value
            $key = empty($instance->name) ? $prop : $instance->name;
            $assignmentList[$key] = $value;
          }
        }
      }
    }

    $result =
      $this->query
        ->update(tableName: $this->tableName)
        ->set(assignmentList: $assignmentList)
        ->where("id=${id}")->execute();

    if ($result->isOK())
    {
      return $this->findOne("id=" . $id);
    }

    return false;
  }

  public function updateRange(array $changeList): array|false
  {
    exit(new NotImplementedErrorResponse(message: get_called_class()));
    return false;
  }

  public function softRemove(int $id): IEntity|stdClass|false
  {
    $className = $this->entity;
    $entity = $this->findOne("id=" . $id);
    $entity = $className::newInstanceFromObject($entity);

    if (is_null($entity))
    {
      exit(new NotFoundErrorResponse());
    }

    $id = $entity->id;
    $now = date(DATE_ATOM);
    $assignmentList = ['deleted_at' => $now];

    if (property_exists($this->entity, 'status'))
    {
      $status = new ReflectionProperty($this->entity, 'status');
      $attributes = $status->getAttributes(Column::class);
      if (!empty($attributes))
      {
        foreach($attributes as $attribute)
        {
          $instance = $attribute->newInstance();
          if ($instance->dataType === SQLDataTypes::ENUM)
          {
            $deleteIsValidStatus = match (gettype($instance->lengthOrValues)) {
              'string' => str_contains($instance->lengthOrValues, 'deleted'),
              'array' => in_array('deleted', $instance->lengthOrValues),
              default => false
            };

            if ($deleteIsValidStatus)
            {
              $entity->status = 'deleted';
              $assignmentList['status'] = 'deleted';
            }
          }
        }
      }
    }

    $result =
      $this->query
        ->update(tableName: $this->tableName)
        ->set(assignmentList: $assignmentList)
        ->where("id=${id}")
        ->execute();

    if ($result->isOK())
    {
      return $entity;
    }

    return false;
  }

  public function softRemoveRange(array $ids): array|false
  {
    Debugger::respond(response: new NotImplementedErrorResponse(message: 'BaseRepository->softRemoveRange()'));
    return false;
  }

  public function remove(int $id): IEntity|stdClass|false
  {
    $entity = $this->testsRepository->findOne("id=" . $id);

    if (is_null($entity))
    {
      exit(new NotFoundErrorResponse());
    }

    $id = $entity->id;
    $result =
      $this->query
        ->deleteFrom(tableName: $this->tableName)
        ->where("id=${id}")
        ->execute();

    if ($result->isOK())
    {
      return $entity;
    }

    return false;
  }

  public function removeRange(array $ids): array|false
  {
    Debugger::respond(response: new NotImplementedErrorResponse(message: 'BaseRepository->removeRange()'));
    return false;
  }

  public function restore(int $id): IEntity|stdClass|false
  {
    $className = $this->entity;
    $entity = $this->findOne("id=" . $id, filterDeleted: false);
    $entity = $className::newInstanceFromObject($entity);

    if (is_null($entity))
    {
      exit(new NotFoundErrorResponse());
    }

    $id = $entity->id;
    $then = '1000-01-01 00:00:00';
    $assignmentList = ['deleted_at' => $then];

    if (property_exists($this->entity, 'status'))
    {
      $status = new ReflectionProperty($this->entity, 'status');
      $attributes = $status->getAttributes(Column::class);
      if (!empty($attributes))
      {
        foreach($attributes as $attribute)
        {
          $instance = $attribute->newInstance();
          if ($instance->dataType === SQLDataTypes::ENUM)
          {
            $deleteIsValidStatus = match (gettype($instance->lengthOrValues)) {
              'string' => str_contains($instance->lengthOrValues, 'active'),
              'array' => in_array('active', $instance->lengthOrValues),
              default => false
            };

            if ($deleteIsValidStatus)
            {
              $entity->status = 'active';
              $assignmentList['status'] = 'active';
            }
          }
        }
      }
    }

    $result =
      $this->query
        ->update(tableName: $this->tableName)
        ->set(assignmentList: $assignmentList)
        ->where("id=${id}")
        ->execute();

    if ($result->isOK())
    {
      $entity->deletedAt = $then;
      return $entity;
    }

    return false;
  }
}

?>