<?php

namespace LifeRaft\Core;

use LifeRaft\Core\Attributes\Injectable;

#[Injectable]
class Service
{
  public function findAll(): Result
  {
    return new Result();
  }

  public function find(int $id): Result
  {
    return new Result();
  }

  public function create(mixed $entity): Result
  {
    return new Result();
  }

  public function update(): Result
  {
    return new Result();
  }

  public function patch(): Result
  {
    return new Result();
  }

  public function delete(): Result
  {
    return new Result();
  }
}

?>