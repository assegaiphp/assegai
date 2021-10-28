<?php

namespace LifeRaft\Database\Interfaces;

use stdClass;

interface IRepository {
  public function commit(): bool;
  public function find(string $conditions): array;
  public function findAll(): array;
  public function findOne(int $id): null|IEntity|stdClass;

  public function add(IEntity $entity): IEntity|stdClass|false;
  public function addRange(array $entities): array|false;
  

  public function softRemove(int $id): IEntity|stdClass|false;
  public function softRemoveRange(array $ids): array|false;
  public function remove(int $id): IEntity|stdClass|false;
  public function removeRange(array $ids): array|false;
}

?>