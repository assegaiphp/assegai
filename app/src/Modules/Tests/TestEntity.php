<?php

namespace LifeRaft\Modules\Tests;

use LifeRaft\Database\Attributes\Columns\Column;
use LifeRaft\Database\Attributes\Columns\EmailColumn;
use LifeRaft\Database\BaseEntity;
use LifeRaft\Database\Queries\SQLDataTypes;

class TestEntity extends BaseEntity
{
  #[EmailColumn]
  public string $email = '';
  
  #[Column(name: 'first_name', alias: 'firstName', dataType: SQLDataTypes::VARCHAR, lengthOrValues: 50)]
  public string $firstName = '';

  #[Column( dataType: SQLDataTypes::ENUM, lengthOrValues: ['active', 'inactive', 'archived', 'deleted'], defaultValue: 'deleted' )]
  public string $status = 'active';
}

?>