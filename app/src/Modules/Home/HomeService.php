<?php

namespace LifeRaft\Modules\Home;

use LifeRaft\Core\Attributes\Injectable;
use LifeRaft\Core\BaseService;
use LifeRaft\Core\Result;

#[Injectable]
class HomeService extends BaseService
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
