<?php

namespace LifeRaft\Modules\Tests;

use LifeRaft\Core\Attributes\Injectable;
use LifeRaft\Database\Attributes\Repository;
use LifeRaft\Database\BaseRepository;

#[Repository(
  entity: TestEntity::class,
  tableName: 'tests'
)]
#[Injectable]
class TestsRepository extends BaseRepository
{
}

?>