<?php

namespace Assegai\Modules\Tests;

use Assegai\Core\Attributes\Injectable;
use Assegai\Database\Attributes\Repository;
use Assegai\Database\BaseRepository;

#[Repository(
  entity: TestEntity::class,
  tableName: 'tests'
)]
#[Injectable]
class TestsRepository extends BaseRepository
{
}

?>