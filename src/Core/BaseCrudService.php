<?php

namespace Assegai\Core;

use Assegai\Core\Attributes\Injectable;
use Assegai\Core\Interfaces\ICRUDService;
use Assegai\Database\BaseRepository;
use Assegai\Database\Interfaces\IEntity;
use Assegai\Database\Queries\FindOptions;
use stdClass;

/**
 * @author A. Masiye <amasiye@gmail.com>
 */

#[Injectable]
class BaseCrudService extends BaseService implements ICRUDService
{
  public function __construct(
    protected BaseRepository $repository
  )
  {
    parent::__construct();
  }

  /**
   * Finds all entities that meed the specified conditions
   * 
   * @param null|string $conditions The conditions for finding the entities.
   * 
   * @return Result Returns a `Result` object.
   */
  public function find(?string $conditions = null, FindOptions $options = new FindOptions()): Result
  {
    $data = $this->repository->find(conditions: $conditions, options: $options);
    return new Result(data: $data);
  }

  /**
   * Finds one entity that meets the specified conditions.
   * 
   * @param string $conditions The conditions for finding the entity.
   * 
   * @return Result Returns a `Result` object.
   */
  public function findOne(string $conditions, FindOptions $options = new FindOptions()): Result
  {
    $data = $this->repository->find(conditions: $conditions, options: $options);
    $data = array_slice(array: $data, offset: 0, length: 1);
    return new Result(data: $data);
  }

  /**
   * Updates a single entity identified by `$id`.
   *
   * @param IEntity|array $entity An object or associative array containing the changes to be made.
   *
   * @return Result Returns a `Result` object.
   */
  public function fullUpdate(IEntity|array $entity): Result
  {
    $id = is_array($entity)
      ? ($entity['id'] ?? 0)
      : ($entity->id ?? 0);
    if (is_array($entity))
    {
      $entity = json_decode(json_encode($entity));
    }
    $result = $this->repository->update(id: $id, changes: $entity);

    return new Result(data: [$result]);
  }

  /**
   * Persists a range of new entities to the repository store.   *
   * @param IEntity $entity The entity to be added/created.
   *
   * @return Result Returns a `Result` object.
   */
  public function create(IEntity $entity): Result
  {
    $result = $this->repository->add(entity: $entity);

    $isOk = is_bool($result) ? $result : true;

    return new Result(data: [$result], isOK: $isOk);
  }

  /**
   * Updates a single entity identified by `$id`.
   * 
   * @param stdClass|array $changes An object or associative array containing the changes to be made.
   *
   * @return Result Returns a `Result` object.
   */
  public function partialUpdate(stdClass|array $changes): Result
  {
    $id = is_array($changes) ? ($changes['id'] ?? 0) : ($changes->id ?? 0);
    if (is_array($changes))
    {
      $changes = json_decode(json_encode($changes));
    }
    $result = $this->repository->update(id: $id, changes: $changes);

    return new Result(data: [$result]);
  }

  /**
   * Restores a soft removed entity identified by `$id`.
   * 
   * @param int $id The id of the removed entity to be restored.
   *
   * @return Result Returns a `Result` object.
   */
  public function restore(int $id): Result
  {
    if (method_exists($this->repository, 'restore'))
    {
      $result = $this->repository->restore(id: $id);
      $isOk = is_bool(value: $result) ? $result : true;
      return new Result(data: [$result], isOK: $isOk);
    }

    return new Result(data: ['restore method does not exist'], isOK: false);
  }

  /**
   * When soft removing an entity, it is not actually removed from your database. 
   * Instead, a deleted_at timestamp is set on the record.
   * 
   * @param int $id The id of the entity to be removed.
   *
   * @return Result Returns a `Result` object.
   */
  public function softRemove(int $id): Result
  {
    $result = $this->repository->softRemove(id: $id);
    $isOk = is_bool(value: $result) ? $result : true;
    return new Result(data: [$result], isOK: $isOk);
  }

  /**
   * Soft removes a range of entities from a given list of entity ids.
   * 
   * @param array $idsList The list of entity ids for the entities to be removed.
   *
   * @return Result Returns a `Result` object.
   */
  public function softRemoveRange(array $idsList): Result
  {
    $result = $this->repository->softRemoveRange(ids: $idsList);
    $isOk = is_bool(value: $result) ? $result : true;
    return new Result(data: [$result], isOK: $isOk);
  }

  /**
   * Removes an entity, identified by `$id`, from the repository store.
   * 
   * @param int $id The id of the entity to be removed.
   *
   * @return Result Returns a `Result` object.
   */
  public function remove(int $id): Result
  {
    $result = $this->repository->remove(id: $id);
    $isOk = is_bool(value: $result) ? $result : true;
    return new Result(data: [$result], isOK: $isOk);
  }

  /**
   * Removes a range of entity, identified by `$idsList` list, from the 
   * repository store.
   * 
   * @param array $idsList The list of ids of the entities to be removed.
   * 
   * @return Result Returns a `Result` object containing results of the 
   * operation.
   */
  public function removeRange(array $idsList): Result
  {
    $result = $this->repository->removeRange(ids: $idsList);
    $isOk = is_bool(value: $result) ? $result : true;
    return new Result(data: [$result], isOK: $isOk);
  }
}

