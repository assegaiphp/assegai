<?php

namespace LifeRaft\Modules\Authentication;

use LifeRaft\Core\Attributes\Injectable;
use LifeRaft\Core\Result;
use LifeRaft\Core\BaseService;
use LifeRaft\Core\Config;
use LifeRaft\Lib\Authentication\Authenticator;
use LifeRaft\Lib\Authentication\Strategies\LocalStrategy;
use stdClass;

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
    $strategy = Config::get('authentication')['default_strategy'];

    if ($entity instanceof stdClass && isset($entity->strategy))
    {
      $strategy = $entity->strategy;
    }
    else if (is_array(value: $entity) && isset($entity['strategy']))
    {
      $strategy = $entity['strategy'];
    }

    $strategy = match($strategy) {
      'local' => new LocalStrategy(),
      'jwt'   => new LocalStrategy(),
      'oauth' => new LocalStrategy(),
      default => new LocalStrategy()
    };

    $authenticator = new Authenticator(strategy: $strategy);

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

  public function validateUser(string $usernam, string $password): Result
  {
    return new Result();
  }
}
