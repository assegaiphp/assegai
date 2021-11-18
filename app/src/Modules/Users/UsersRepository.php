<?php

namespace Assegai\Modules\Users;

use Assegai\Core\Attributes\Injectable;
use Assegai\Database\Attributes\Repository;
use Assegai\Database\BaseRepository;

#[Repository(
  entity: UserEntity::class,
  tableName: 'users'
)]
#[Injectable]
class UsersRepository extends BaseRepository
{
}

