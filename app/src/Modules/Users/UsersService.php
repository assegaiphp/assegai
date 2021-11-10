<?php

namespace LifeRaft\Modules\Users;

use LifeRaft\Core\Attributes\Injectable;
use LifeRaft\Core\BaseCrudService;
use LifeRaft\Database\Interfaces\IEntity;
use LifeRaft\Core\Result;

#[Injectable]
class UsersService extends BaseCrudService
{
  public function __construct()
  {
    parent::__construct(repository: new UsersRepository);
  }

  public function create(IEntity $entity): Result
  {
    if (empty($entity->username))
    {
      $entity->username = $entity->email;
    }

    return parent::create(entity: $entity);
  }
}
