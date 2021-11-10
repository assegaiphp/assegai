<?php

namespace LifeRaft\Core;

use LifeRaft\Core\Attributes\Injectable;
use LifeRaft\Database\Interfaces\IEntity;
use LifeRaft\Database\Interfaces\IRepository;
use stdClass;

/**
 * @author A. Masiye <amasiye@gmail.com>
 */

#[Injectable]
class BaseCrudService extends BaseService
{
  public function __construct(
    protected IRepository $repository
  )
  {
    parent::__construct();
  }
  
  public function find(?string $conditions): Result
  {
    $data = $this->repository->find(conditions: $conditions);
    return new Result(data: $data);
  }

  /**
   * Finds one entity that meets the specified conditions.
   * 
   * @param string $conditions The conditions by which to find the entity.
   * 
   * @return null|IEntity|stdClass Returns an instance of the entity if found 
   * otherwise `null`.
   */
  public function findOne(string $conditions): Result
  {
    $data = $this->repository->find(conditions: $conditions);
    $data = array_slice(array: $data, offset: 0, length: 1);
    return new Result(data: $data);
  }

  /**
   * Persists a range of new entities to the repository store.   * 
   * @param array $entities The list entities to be added/created.
   * 
   * @return array|false Returns a list of successfully added entities 
   * or `FALSE` otherwise.
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
   * @param IEntity $entity An object containing the changes to be made.
   * 
   * @return IEntity|stdClass|false Returns an instance of the updated entity 
   * if successful or `FALSE` otherwise.
   */
  public function fullUpdate(IEntity|array $entity): Result
  {
    $id = is_array($entity) ? (isset($entity['id']) ? $entity['id'] : 0) : (isset($entity->id) ? $entity->id : 0);
    if (is_array($entity))
    {
      $entity = json_decode(json_encode($entity));
    }
    $result = $this->repository->update(id: $id, changes: $entity);

    return new Result(data: [$result]);
  }

  /**
   * Updates a single entity identified by `$id`.
   * 
   * @param IEntity $changes An object containing the changes to be made.
   * 
   * @return IEntity|stdClass|false Returns an instance of the updated entity 
   * if successful or `FALSE` otherwise.
   */
  public function partialUpdate(stdClass|array $changes): Result
  {
    $id = is_array($changes) ? (isset($changes['id']) ? $changes['id'] : 0) : (isset($changes->id) ? $changes->id : 0);
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
   * @return IEntity|stdClass|false Returns an instance of the restored enity 
   * if successful or false otherwise.
   */
  public function restore(int $id): Result
  {
    if (method_exists($this->repository, 'restore')) {
      $result = $this->repository->restore(id: $id);
      $isOk = is_bool(value: $result) ? $result : true;
      return new Result(data: [$result], isOK: $isOk);
    }
  }

  /**
   * When soft removing an entity, it is not actually removed from your database. 
   * Instead, a deleted_at timestamp is set on the record.
   * 
   * @param int $id The id of the entity to be removed.
   * 
   * @return IEntity|stdClass|false Returns an instance of the removed enity 
   * if successful or false otherwise.
   */
  public function softRemove(int $id): Result
  {
    $result = $this->repository->softRemove(id: $id);
    $isOk = is_bool(value: $result) ? $result : true;
    return new Result(data: [$result], isOK: $isOk);
  }

  /**
   * Soft removes a range of entities from a given list of enity ids.
   * 
   * @param array $ids The list of entity ids for the entities to be removed.
   * 
   * @return array|false Returns a list of ids if successful 
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
   * @param int $ids The id of the entity to be removed.
   * 
   * @return Result Returns a `Result` object containing results of the 
   * operation.
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
   * @param array $idsList The list of ids of the enitities to be removed.
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

?>