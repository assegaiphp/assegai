<?php

namespace Assegai\Database;

use Assegai\Core\Exceptions\NotImplmentedException;
use Assegai\Database\Attributes\Repository;
use Assegai\Database\Interfaces\IEntity;
use stdClass;

final class EntityManager
{
  /**
   * @param array<Repository> $repositories
   */
  public function __construct(
    private array $repositories = []
  )
  {
  }

  /**
   * @param IEntity|array<IEntity> $entity
   * @return void
   */
  public function save(IEntity|array $entity): void
  {
    if ($entity instanceof IEntity)
    {

    }
    else
    {

    }
  }

  /**
   * @param string $entityName
   * @param array|stdClass|IEntity $entity
   * @return void
   * @throws NotImplmentedException
   */
  public function insert(string $entityName, array|stdClass|IEntity $entity): void
  {
    // TODO: Implement insert
    throw new NotImplmentedException();
    throw new NotImplmentedException("EntityManager::insert() Not Implemented");
  }

  /**
   * @param string $entityName
   * @param string|stdClass|array $conditions
   * @param stdClass|array|IEntity $entity
   * @return void
   * @throws NotImplmentedException
   */
  public function update(string $entityName, string|stdClass|array $conditions, stdClass|array|IEntity $entity): void
  {
    // TODO: Implement update
    throw new NotImplmentedException("EntityManager::update() Not Implemented");
  }

  /**
   * @param string $entityName
   * @param IEntity|array<IEntity> $entity
   * @return void
   * @throws NotImplmentedException
   */
  public function upsert(string $entityName, IEntity|array $entity): void
  {
    // TODO: Implement upsert
    throw new NotImplmentedException("EntityManager::upsert() Not Implemented");
  }

  /**
   * @param IEntity|array<IEntity> $entity
   * @return void
   * @throws NotImplmentedException
   */
  public function remove(IEntity|array $entity): void
  {
    // TODO: Implement remove
    throw new NotImplmentedException("EntityManager::remove() Not Implemented");
  }

  /**
   * @param string $entityName
   * @param int|array|stdClass $conditions
   * @return void
   * @throws NotImplmentedException
   */
  public function delete(string $entityName, int|array|stdClass $conditions): void
  {
    // TODO: Implement delete
    throw new NotImplmentedException("EntityManager::delete() Not Implemented");
  }

  public function count(string $entityName, array|stdClass $conditions): void
  {
    // TODO: Implement count
    throw new NotImplmentedException("EntityManager::count() Not Implemented");
  }

  /**
   * @param string $entityType
   * @return IEntity|array<IEntity>|null
   */
  public function find(string $entityType, ?FindOptions $findOptions): null|IEntity|array
  {
    $sql = "SELECT ";

    return null;
  }

  public function findBy(): null|IEntity|array
  {
    return null;
  }
}

