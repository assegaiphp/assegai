<?php

namespace Assegai\Modules\Tests;

use Assegai\Database\Attributes\Columns\Column;
use Assegai\Database\Attributes\Columns\EmailColumn;
use Assegai\Database\Attributes\Columns\PasswordColumn;
use Assegai\Database\Attributes\Entity;
use Assegai\Database\BaseEntity;
use Assegai\Database\Queries\SQLDataTypes;

#[Entity(tableName: 'tests', database: 'assegai_test')]
class TestEntity extends BaseEntity
{
  #[EmailColumn]
  public string $email = '';

  #[PasswordColumn]
  public string $password = '';

  #[Column(name: 'first_name', alias: 'firstName', dataType: SQLDataTypes::VARCHAR, lengthOrValues: 50)]
  public string $firstName = '';

  #[Column( dataType: SQLDataTypes::ENUM, lengthOrValues: ['active', 'inactive', 'archived', 'deleted'], defaultValue: 'deleted' )]
  public string $status = 'active';

  #[Column(name: 'total_votes', dataType: SQLDataTypes::INT)]
  public int $totalVotes = 0;
}

?>