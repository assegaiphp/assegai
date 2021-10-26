<?php

namespace LifeRaft\Database\Interfaces;

use stdClass;

interface IRepository {
  public function commit(): bool;
  public function find(string $conditions): array;
  public function findAll(): array;
  public function findOne(int $id): null|IEntity|stdClass;

  public function add(IEntity $entity): void;
  public function addRange(array $entities): void;
  

  public function remove(IEntity $entity): void;
  public function removeRange(array $entities): void;
}

?>