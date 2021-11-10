<?php

namespace LifeRaft\Database\Interfaces;

use stdClass;

/**
 * @author A. Masiye <amasiye@gmail.com>
 */
interface IRepository {
  public function commit(): bool;

  /**
   * Finds all entities that meet the specified conditions.
   * 
   * @param string $conditions The conditions by which to filter the 
   * selectable entities.
   * 
   * @return array Returns a list of entities that meet the specified 
   * conditions.
   */
  public function find(?string $conditions): array;

  /**
   * Finds all entities in the repository.
   * 
   * @return array Returns a list of all entities in the repository. 
   */
  public function findAll(): array;

  /**
   * Finds one entity that meets the specified conditions.
   * 
   * @param string $conditions The conditions by which to find the entity.
   * 
   * @return null|IEntity|stdClass Returns an instance of the entity if found 
   * otherwise `null`.
   */
  public function findOne(string $conditions): null|IEntity|stdClass;

  /**
   * Persists a new entity to the repository store.
   * 
   * @param IEntity $entity The entity to be added/created.
   * 
   * @return IEntity|stdClass|false Returns an instance of newly added entity 
   * if successful or `FALSE` otherwise.
   */
  public function add(IEntity $entity): IEntity|stdClass|false;
  
  /**
   * Persists a range of new entities to the repository store.   * 
   * @param array $entities The list entities to be added/created.
   * 
   * @return array|false Returns a list of successfully added entities 
   * or `FALSE` otherwise.
   */
  public function addRange(array $entities): array|false;

  /**
   * Updates a single entity identified by `$id`.
   * 
   * @param int|string $id The id of the entity to be updated.
   * @param IEntity $changes An object containing the changes to be made.
   * 
   * @return IEntity|stdClass|false Returns an instance of the updated entity 
   * if successful or `FALSE` otherwise.
   */
  public function update(int|string $id, IEntity|stdClass $changes): IEntity|stdClass|false;

  /**
   * Updates a range of entities.
   * 
   * @param array $changeList An array of objects containing the changes to 
   * be made.
   * 
   * @return array Returns a list of successfullly changed objects or `FALSE` 
   * otherwise.
   */
  public function updateRange(array $changeList): array|false;

  /**
   * Restores a soft removed entity identified by `$id`.
   * 
   * @param int $id The id of the removed entity to be restored.
   * 
   * @return IEntity|stdClass|false Returns an instance of the restored enity 
   * if successful or false otherwise.
   */
  public function restore(int $id): IEntity|stdClass|false;

  /**
   * When soft removing an entity, it is not actually removed from your database. 
   * Instead, a deleted_at timestamp is set on the record.
   * 
   * @param int $id The id of the entity to be removed.
   * 
   * @return IEntity|stdClass|false Returns an instance of the removed enity 
   * if successful or false otherwise.
   */
  public function softRemove(int $id): IEntity|stdClass|false;

  /**
   * Soft removes a range of entities from a given list of enity ids.
   * 
   * @param array $ids The list of entity ids for the entities to be removed.
   * 
   * @return array|false Returns a list of ids if successful 
   */
  public function softRemoveRange(array $ids): array|false;

  /**
   * Removes an entity, identified by `$id`, from the repository store.
   * 
   * @param int $id The id of the entity to be removed.
   * 
   * @return IEntity|stdClass|false Returns an instance of the removed enity 
   * if successful or false otherwise.
   */
  public function remove(int $id): IEntity|stdClass|false;

  /**
   * Removes a range of entity, identified by `$ids` list, from the repository store.
   * 
   * @param array $ids The list of ids of the enitities to be removed.
   * 
   * @return IEntity|stdClass|false Returns an instance of the removed enity 
   * if successful or false otherwise.
   */
  public function removeRange(array $ids): array|false;
}

?>