<?php

namespace Assegai\Database\Management;

use Assegai\Core\Exceptions\ClassNotFoundException;
use Assegai\Core\Exceptions\EmptyCriteriaException;
use Assegai\Core\Exceptions\GeneralSQLQueryException;
use Assegai\Core\Exceptions\IllegalTypeException;
use Assegai\Core\Exceptions\NotImplmentedException;
use Assegai\Core\Exceptions\SaveException;
use Assegai\Core\Responses\NotFoundErrorResponse;
use Assegai\Core\Result;
use Assegai\Database\Attributes\Repository;
use Assegai\Database\DataSource;
use Assegai\Database\Interfaces\IEntity;
use Assegai\Database\Queries\SQLQuery;
use Assegai\Database\Queries\SQLQueryResult;
use Assegai\Util\Filter;
use Exception;
use PDOStatement;
use stdClass;

final class EntityManager
{
  /**
   * Once created and then reused by repositories.
   */
  protected array $repositories = [];

  protected array $readonlyColumns = ['id', 'createdAt', 'updatedAt', 'deletedAt'];

  /**
   * @param array<Repository> $repositories
   */
  public function __construct(
    protected DataSource $connection,
    protected ?SQLQuery $query = null
  )
  {
    $this->query = $query ?? new SQLQuery(db: $connection->db);
  }

  public function lastInsertId(): ?int
  {
    return $this->query->lastInsertId();
  }

  /**
   * Executes a raw SQL query and returns the raw database results.
   * 
   * @param string $query
   * @param array $parameters
   * 
   * @return PDOStatement|false Returns a PDOStatement object, or FALSE on failure.
   * @link https://php.net/manual/en/pdo.query.php
   */
  public function query(string $query, array $parameters = []): PDOStatement|false
  {
    // TODO: Add support for all raw query parameters e.g. mode, ...fetch_mode_args
    return $this->connection->db->query($query);
  }

  /**
   * Saves all given entities in the database.
   * If entities do not exist in the database then inserts, otherwise updates.
   * 
   * @param IEntity|array<IEntity> $entity
   * 
   * @return IEntity|array
   * @throws IllegalTypeException
   */
  public function save(IEntity|array $targetOrEntity): IEntity|array
  {
    $results = [];

    if ($targetOrEntity instanceof IEntity)
    {
      $saveResult = new SQLQueryResult(data: [], isOK: false);

      if (empty($targetOrEntity->id))
      {
        $saveResult = $this->insert(entityClass: $targetOrEntity::class, entity: $targetOrEntity);
      }
      else if ($entity = $this->findBy($targetOrEntity::class, new FindWhereOptions(conditions: ['id' => $targetOrEntity->id])))
      {
        $saveResult = $this->update(entityClass: $targetOrEntity::class, conditions: ['id' => $targetOrEntity->id], entity: $targetOrEntity);
      }
      else
      {
        exit(new NotFoundErrorResponse(message: "The resouce with ID #{$targetOrEntity->id} could not be found"));
      }

      // TODO: Check if errors occured
      if ($saveResult->isError())
      {
        throw new SaveException(details: __CLASS__ . '::' . __METHOD__ . ' on line ' . __LINE__);
      }

      return $this->findBy($targetOrEntity::class, new FindWhereOptions(conditions: ['id' => $this->query->lastInsertId()]));
    }
    else if(is_array($targetOrEntity))
    {
      foreach ($targetOrEntity as $entity)
      {
        $results[] = $this->save(targetOrEntity: $entity);
      }
    }
    else
    {
      throw new IllegalTypeException(expected: IEntity::class, actual: $targetOrEntity::class);
    }

    return $results;
  }

  /**
   * Validates the given entity name. If an invalid entity name is given, then a 
   * `ClassNotFoundException` is thrown.
   * 
   * @throws ClassNotFoundException
   */
  public static function validateEntityName(string $entityClass): void
  {    
    if (!class_exists($entityClass))
    {
      throw new ClassNotFoundException(className: $entityClass);
    }

    if (!is_a($entityClass, IEntity::class, true))
    {
      throw new IllegalTypeException(expected: IEntity::class, actual: $entityClass);
    }
  }

  /**
   * Creates a new entity instance or instances. Optionally accepts an object literal with entity 
   * properties which will be written into newly created Entity object.
   * 
   * @param string $entityClass
   * @param null|stdClass|array $plainObjectOrObjects an object or array literal with entity properties
   * 
   * @return IEntity|array<IEntity> Returns a newly created Entity object
   * @throws ClassNotFoundException
   */
  public function create(string $entityClass, null|stdClass|array $plainObjectOrObjects = null): IEntity|array
  {
    $this->validateEntityName(entityClass: $entityClass);

    $entity = new $entityClass;

    if (!empty($plainObjectOrObjects))
    {
      foreach ($plainObjectOrObjects as $key => $value)
      {
        if (property_exists($entity, $key))
        {
          $entity->$key = $value;
        }
      }
    }

    return $entity;
  }

  /**
   * Merges multiple entities into a single entity.
   * 
   * @param string $entityClass
   * @param mixed ...$entities
   * 
   * @return IEntity Returns a single entity
   * @throws ClassNotFoundException
   */
  public function merge(string $entityClass, ...$entities): IEntity
  {
    $this->validateEntityName(entityClass: $entityClass);

    $entity = new $entityClass;

    if ($entity instanceof IEntity)
    {
      $object = json_decode($entity->toJSON());

      foreach ($entities as $item)
      {
        if (is_object($item) || is_array($item))
        {
          $object = (object) array_merge((array) $object, (array) $item);
        }
      }

      foreach ($object as $prop => $value)
      {
        if (property_exists($entity, $prop))
        {
          $entity->$prop = $value;
        }
      }
    }

    return $entity;
  }

  /**
   * Creates a new entity from the given plain php object. If the entity already exist in the database, then
   * it loads it (and everything related to it), replaces all values with the new ones from the given object
   * and returns this new entity. This new entity is actually a loaded from the db entity with all properties
   * replaced from the new object.
   */
  public function preload(string $entityClass, stdClass $entityLike): ?IEntity
  {
    $entity = $this->find(entityClass: $entityClass);

    if (empty($entity))
    {
      $entity = $this->create(entityClass: $entityClass, plainObjectOrObjects: $entityLike);
    }

    return $this->merge($entityClass, $entity, $entityLike);
  }

  /**
   * Inserts a given entity into the database.
   * Unlike the save method executes a primitive operation without 
   * cascades, relations and other operations included.
   * Executes a fast and efficient `INSERT` query.
   * Does not check if the entity exist in the database, so the query will fail if 
   * duplicate entity is being inserted.
   * You can execute bulk inserts using this method.
   * 
   * @param string $entityClass
   * @param array|stdClass|IEntity $entity
   * @return SQLQueryResult
   * @throws NotImplmentedException
   */
  public function insert(
    string $entityClass,
    array|stdClass|IEntity $entity
  ): SQLQueryResult
  {
    // TODO: #81 Implement EntityManager::insert @amasiye
    $instance = $this->create(entityClass: $entityClass, plainObjectOrObjects: $entity->toPlainObject());

    $columns = $instance->columns(exclude: $this->readonlyColumns);
    $values = $instance->values(exclude: $this->readonlyColumns);

    $result =
      $this
        ->query
        ->insertInto(tableName: $instance->getTableName())
        ->singleRow(columns: $columns)
        ->values(valuesList: $values)
        ->execute();

    if ($result->isError())
    {
      throw new GeneralSQLQueryException(message: sprintf("%s - %s", $result, __METHOD__));
    }

    return $result;
  }

  /**
   * Updates entity partially. Entity can be found by a given condition(s).
   * Unlike save method executes a primitive operation without cascades, relations and other operations included.
   * Executes fast and efficient UPDATE query.
   * Does not check if entity exist in the database.
   * Condition(s) cannot be empty.
   * 
   * @param string $entityClass
   * @param string|stdClass|array $conditions
   * @param stdClass|array|IEntity $entity
   * @return Result
   * @throws NotImplmentedException
   */
  public function update(
    string $entityClass,
    string|stdClass|array $conditions,
    stdClass|array|IEntity $entity
  ): SQLQueryResult
  {
    // TODO: #82 Implement EntityManager::update @amasiye
    $this->validateConditions(conditions: $conditions, methodName: __METHOD__);
    $conditionString = '';

    if (!is_string($conditions))
    {
      foreach ($conditions as $key => $value)
      {
        $conditionString .= "$key=" . (is_numeric($value) ? $value : "'$value'");
      }
    }
    else
    {
      $conditionString = $conditions;
    }

    $plainObjectOrObjects = match(true) {
      is_a($entity, IEntity::class) => $entity->toPlainObject(),
      default => $entity
    };

    $instance = $this->create(entityClass: $entityClass, plainObjectOrObjects: $plainObjectOrObjects);

    $result =
      $this
        ->query
        ->update(tableName: $instance->getTableName())
        ->set(assignmentList: $instance->columnValuePairs(exclude: $this->readonlyColumns))
        ->where(condition: $conditionString)
        ->execute();

    if ($result->isError())
    {
      throw new GeneralSQLQueryException(message: sprintf("%s - %s", $result, __METHOD__));
    }

    return $result;
  }

  /**
   * @param string $entityClass
   * @param IEntity|array<IEntity> $entity
   * @return Result
   * @throws NotImplmentedException
   */
  public function upsert(string $entityClass, IEntity|array $entity): SQLQueryResult
  {
    // TODO: #83 Implement EntityManager::upsert @amasiye
    throw new NotImplmentedException("EntityManager::upsert() Not Implemented");
  }


  /**
   * Removes a given entity from the database.
   * 
   * @param IEntity|array<IEntity> $entity
   * @return void
   * @throws NotImplmentedException
   */
  public function remove(
    IEntity|array $entityOrEntities,
    ?RemoveOptions $removeOptions = null
  ): null|IEntity|array
  {
    // TODO: #84 Implement EntityManager::remove @amasiye
    $result = null;

    if ($entityOrEntities instanceof IEntity)
    {
      $statement =
        $this
          ->query
          ->deleteFrom(tableName: $entityOrEntities->getTableName())
          ->where("id={$entityOrEntities->id}");

      $result = $statement->execute();

      if ($result->isError())
      {
        throw new GeneralSQLQueryException(message: sprintf("%s - %s", $result, __METHOD__));
      }

      return $entityOrEntities;
    }
    else if (is_array($entityOrEntities))
    {
      $savedEntities = [];
      foreach ($entityOrEntities as $entity)
      {
        $savedEntities[] = $this->softRemove(entityOrEntities: $entity);
      }

      return $savedEntities;
    }

    throw new NotImplmentedException("EntityManager::remove() Not Implemented");
    return $result;
  }

  /**
   * Records the delete date of a given entity.
   * 
   * @param IEntity|array $entityOrEntities
   * 
   * @return null|IEntity|array<IEntity> Returns the removed entities.
   */
  public function softRemove(
    IEntity|array $entityOrEntities,
    ?RemoveOptions $removeOptions = null
  ): null|IEntity|array
  {
    $result = null;
    $deletedAt = date(DATE_ATOM);

    if ($entityOrEntities instanceof IEntity)
    {
      // TODO: #87 Resolve deletedAt column name @amasiye
      $statement =
        $this
        ->query
        ->update(tableName: $entityOrEntities->getTableName())
        ->set([Filter::getDeleteDateColumnName(entity: $entityOrEntities) => $deletedAt])
        ->where("id={$entityOrEntities->id}");

      $result = $statement->execute();

      if ($result->isError())
      {
        throw new GeneralSQLQueryException(message: sprintf("%s - %s", $result, __METHOD__));
      }

      // TODO: #88 Verify that delete occured @amasiye
      $result = $entityOrEntities;
    }
    else if (is_array($entityOrEntities))
    {
      $savedEntities = [];
      foreach ($entityOrEntities as $entity)
      {
        $savedEntities[] = $this->softRemove(entityOrEntities: $entity);
      }

      return $savedEntities;
    }

    return $result;
  }

  /**
   * Deletes entitites by a given condition(s).
   * 
   * Unlike the save method, it executes a primitive operation without cascades, 
   * relations and other operations included.
   * Executes a fast and effecient `DELETE` query.
   * Does not check if the entity exists in the database.
   * Condition(s) cannot be empty.
   * 
   * @param string $entityClass
   * @param int|array|stdClass $conditions The deletion conditions.
   * 
   * @return null|IEntity|array<IEntity> Returns the removed entities.
   * @throws NotImplmentedException
   */
  public function delete(
    string $entityClass, 
    int|array|stdClass $conditions
  ): null|IEntity|array
  {
    // TODO: #85 Implement EntityManager::delete @amasiye
    $this->validateConditions(conditions: $conditions, methodName: __METHOD__);

    $entity = $this->create(entityClass: $entityClass);

    $statement =
      $this
        ->query
        ->deleteFrom(tableName: $entity->getTableName())
        ->where(condition: $this->getConditionsString(conditions: $conditions));

    $deletionResult = $statement->execute();

    if ($deletionResult->isError())
    {
      throw new GeneralSQLQueryException(message: sprintf("%s - %s", $deletionResult, __METHOD__));
    }

    return $deletionResult->value();
  }

  /**
   * Restores entities by a given condition(s).
   * Unlike save method executes a primitive operation without cascades, relations and other operations included.
   * Executes fast and efficient DELETE query.
   * Does not check if entity exist in the database.
   * Condition(s) cannot be empty.
   */
  public function restore(
    string $entityClass,
    int|array|stdClass $conditions
  ): null|IEntity|array
  {
    // TODO: #89 Implement EntityManager::restore() @amasiye
    $entity = $this->create(entityClass: $entityClass);

    // TODO: resolve deleted_at column name
    $statement =
      $this
        ->query
        ->update(tableName: $entity->getTableName())
        ->set([Filter::getDeleteDateColumnName(entity: $entity) => NULL])
        ->where(condition: $this->getConditionsString(conditions: $conditions));

    $restoreResult = $statement->execute();

    if ($restoreResult->isError())
    {
      throw new GeneralSQLQueryException(message: sprintf("%s - %s", $restoreResult, __METHOD__));
    }

    return $restoreResult->value();
  }

  /**
   * Counts entities that match given options.
   * Useful for pagination.
   * 
   * @param string $entityClass
   * @param null|FindOptions $findOptions
   * 
   * @return int Returns the count of entities that match the given options
   */
  public function count(
    string $entityClass,
    ?FindOptions $options = null,
  ): int
  {
    $entity = $this->create(entityClass: $entityClass);

    $statement =
      $this
        ->query
        ->select()
        ->count()
        ->from(tableReferences: $entity->getTableName());

    if (!empty($findOptions))
    {
      $statement = $statement->where(condition: $options);
    }

    $result = $statement->execute();

    if ($result->isError())
    {
      throw new GeneralSQLQueryException(message: sprintf("%s - %s", $result, __METHOD__));
    }

    return $result->value()[0]['COUNT(*)'] ?? 0;
  }

  /**
   * Find entites that match the given `FindOptions`.
   * 
   * @param string $entityClass
   * @param null|FindOptions $findOptions
   * 
   * @return IEntity|array<IEntity>|null
   */
  public function find(string $entityClass, ?FindOptions $findOptions = new FindOptions()): ?array
  {
    $entity = $this->create(entityClass: $entityClass);
    $statement
      = $this
        ->query
        ->select()
        ->all(columns: $entity->columns(exclude: $findOptions->exclude))
        ->from(tableReferences: $entity->getTableName());

    if (!empty($findOptions))
    {
      $statement = $statement->where(condition: $findOptions);
    }

    $result = $statement->execute();

    if ($result->isError())
    {
      throw new GeneralSQLQueryException(message: sprintf("%s - %s", $result, __METHOD__));
    }

    return $result->value();
  }

  /**
   * Finds entities that match given `FindWhereOptions`.
   * 
   * @return null|array<IEntity> Returns a list of entities that match the given `FindWhereOptions`.
   */
  public function findBy(string $entityClass, FindWhereOptions $where): ?array
  {
    $entity = $this->create(entityClass: $entityClass);
    $statement
      = $this
        ->query
        ->select()
        ->all(columns: $entity->columns())
        ->from(tableReferences: $entity->getTableName())
        ->where(condition: $where);

    $result = $statement->execute();

    if ($result->isError())
    {
      throw new GeneralSQLQueryException(message: sprintf("%s - %s", $result, __METHOD__));
    }

    return $result->value();
  }

  /**
   * Finds entities that match given find options.
   * Also counts all entities that match given conditions,
   * but ignores pagination settings (from and take options).
   * 
   * @return array<[IEntity,int]>
   */
  public function findAndCount(
    string $entityClass,
    ?FindManyOptions $options = null
  ): array
  {
    $entities = $this->find(entityClass: $entityClass, findOptions: $options);

    return ['entities' => $entities, 'count' => count($entities)];
  }

  /**
   * Finds entities that match given WHERE conditions.
   * Also counts all entities that match given conditions,
   * but ignores pagination settings (from and take options).
   */
  public function findAndCountBy(
    string $entityClass,
    FindWhereOptions $where
  ): array
  {
    $entities = $this->findBy(entityClass: $entityClass, where: $where);

    return ['entities' => $entities, 'count' => count($entities)];
  }

  /**
   * Finds first entity by a given find options.
   * If entity was not found in the database - returns null.
   * 
   * @param string $entityClass
   * @param FindOptions|FindOneOptions $options
   * 
   * @return null|IEntity Returns the entity if found, null otherwise.
   */
  public function findOne(
    string $entityClass,
    FindOptions|FindOneOptions $options
  ): ?IEntity
  {
    $found = $this->find(entityClass: $entityClass, findOptions: $options);

    if (empty($found[0]))
    {
      return null;
    }
    
    return $entityClass::newInstanceFromArray(array: $found[0]);
  }

  /**
   * @throws EmptyCriteriaException
   */
  private function validateConditions(string|stdClass|array $conditions, string $methodName): void
  {
    if (empty($conditions))
    {
      throw new EmptyCriteriaException(methodName: $methodName);
    }
  }

  /**
   * @param int|stdClass|array $conditions
   * 
   * @return string Retursn an SQL condition string
   */
  private function getConditionsString(int|stdClass|array $conditions): string
  {
    $separator = ', ';
    $conditionsString = '';

    if (empty($conditions))
    {
      return '';
    }

    if (is_int($conditions))
    {
      $conditionsString = sprintf("id=%s", $conditions);
    }
    else
    {
      foreach ($conditions as $key => $value)
      {
        $conditionsString .= sprintf("%s=%s%s", $key, (is_numeric($value) ? $value : "'$value'"), $separator);
      }
    }

    return trim($conditionsString, $separator);
  }
}

