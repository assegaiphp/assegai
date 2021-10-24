<?php

namespace LifeRaft\Database\Interfaces;

interface IRepository {
  public function get(int $id): IEntity|false;
  public function getAll(): array;
  public function find(IEntity $entity): array;

  public function add(IEntity $entity): void;
  public function addRange(IEntity $entity): void;
  

  public function remove(IEntity $entity): void;
  public function removeRange(array $entities): void;
}

?>