<?php

namespace LifeRaft\Modules\Authentication;

use LifeRaft\Core\Attributes\Injectable;
use LifeRaft\Core\Result;
use LifeRaft\Core\BaseService;

#[Injectable]
class AuthenticationService extends BaseService
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
    return new Result(data: [$entity]);
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
