<?php

namespace Assegai\Modules\Users;

use Assegai\Core\Attributes\Injectable;
use Assegai\Core\BaseCrudService;
use Assegai\Database\Interfaces\IEntity;
use Assegai\Core\Result;

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
