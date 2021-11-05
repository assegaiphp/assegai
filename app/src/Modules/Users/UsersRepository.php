<?php

namespace LifeRaft\Modules\Users;

use LifeRaft\Core\Attributes\Injectable;
use LifeRaft\Database\Attributes\Repository;
use LifeRaft\Database\BaseRepository;

#[Repository(
  entity: UserEntity::class,
  tableName: 'users'
)]
#[Injectable]
class UsersRepository extends BaseRepository
{
}

?>