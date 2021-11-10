<?php

namespace LifeRaft\Modules\Users;

use LifeRaft\Core\Attributes\Injectable;
use LifeRaft\Core\BaseCrudService;

#[Injectable]
class UsersService extends BaseCrudService
{
  public function __construct()
  {
    parent::__construct(repository: new UsersRepository);
  }
}
