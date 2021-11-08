<?php

namespace LifeRaft\Modules\Tests;

use LifeRaft\Core\Attributes\Injectable;
use LifeRaft\Core\Result;
use LifeRaft\Core\BaseService;
use stdClass;

#[Injectable]
class TestsService extends BaseService
{
  public function find(array $conditions): Result
  {
    return new Result();
  }

  public function create(mixed $entity): Result
  {
    return new Result();
  }

  public function update(stdClass|array $entity): Result
  {
    return new Result();
  }

  public function patch(stdClass|array $params): Result
  {
    return new Result();
  }

  public function delete(array $conditions): Result
  {
    return new Result();
  }

  public function deleteOne(int $id): Result
  {
    return new Result();
  }
}
