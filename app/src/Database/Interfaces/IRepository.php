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
  

  public function remove(IEntity $entity): IEntity|stdClass|false;
  public function removeRange(array $entities): array|false;
}

?>