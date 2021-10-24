<?php

namespace LifeRaft\Modules\Tests;

use LifeRaft\Core\Attributes\Injectable;
use LifeRaft\Database\Attributes\Repository;
use LifeRaft\Database\BaseRepository;
use LifeRaft\Database\Interfaces\IEntity;
use LifeRaft\Modules\Test\TestEntity;

#[Repository(
  entity: TestEntity::class,
  tableName: 'tests'
)]
#[Injectable]
class TestsRepository extends BaseRepository
{
  public function find(mixed $predicate): array
  {
    return [];
  }

  public function get(int $id): IEntity|false
  {
    return new IEntity();
  }

  public function getAll(): array
  {
    return [];
  }

  public function add(IEntity $obj): void
  {
  }

  public function addRange(IEntity $entity): void
  {
  }

  public function remove(IEntity $obj): void
  {
  }

  public function removeRange(array $entities): void
  {
  }
}

?>