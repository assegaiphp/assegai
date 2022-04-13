<?php

namespace Assegai\Database;

use Assegai\Database\Interfaces\IEntity;
use Assegai\Database\Interfaces\IRepository;
use Assegai\Database\Queries\FindOptions;
use stdClass;

class CustomRepository implements IRepository
{
  public function __construct(
    public readonly string $entity,
    public readonly DataSource $connection
  ) { }

  public function commit(): bool
  {
    // TODO: Implement CustomRepository::commit()
    return false;
  }

  public function find(?string $conditions, FindOptions $options = new FindOptions()): array
  {
    // TODO: Implement CustomRepository::find()
    return [];
  }

  public function findAll(FindOptions $options = new FindOptions()): array
  {
    // TODO: Implement CustomRepository::findAll()
    return [];
  }

  public function findOne(string $conditions, FindOptions $options = new FindOptions()): null|IEntity|stdClass
  {
    // TODO: Implement CustomRepository::findOne()
    return null;
  }

  public function add(IEntity $entity): IEntity|stdClass|false
  {
    // TODO: Implement CustomRepository::add()
    return false;
  }

  public function addRange(array $entities): array|false
  {
    // TODO: Implement CustomRepository::addRange()
    return false;
  }

  public function update(int|string $id, IEntity|stdClass $changes): IEntity|stdClass|false
  {
    // TODO: Implement CustomRepository::update()
    return false;
  }

  public function updateRange(array $changeList): array|false
  {
    // TODO: Implement CustomRepository::updateRange()
    return false;
  }

  public function restore(int $id): IEntity|stdClass|false
  {
    // TODO: Implement CustomRepository::restore()
    return false;
  }

  public function softRemove(int $id): IEntity|stdClass|false
  {
    // TODO: Implement CustomRepository::softRemove()
    return false;
  }

  public function softRemoveRange(array $ids): array|false
  {
    // TODO: Implement CustomRepository::softRemoveRange()
    return false;
  }

  public function remove(int $id): IEntity|stdClass|false
  {
    // TODO: Implement CustomRepository::remove()
    return false;
  }

  public function removeRange(array $ids): array|false
  {
    // TODO: Implement CustomRepository::removeRange()
    return false;
  }
}