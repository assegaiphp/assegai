<?php

namespace LifeRaft\Modules\Tests;

use LifeRaft\Database\Attributes\Columns\Column;
use LifeRaft\Database\Attributes\Columns\CreateDateColumn;
use LifeRaft\Database\Attributes\Columns\DeleteDateColumn;
use LifeRaft\Database\Attributes\Columns\EmailColumn;
use LifeRaft\Database\Attributes\Columns\PrimaryGeneratedColumn;
use LifeRaft\Database\Attributes\Columns\UpdateDateColumn;
use LifeRaft\Database\BaseEntity;
use LifeRaft\Database\Queries\SQLDataTypes;

class TestEntity extends BaseEntity
{
  #[PrimaryGeneratedColumn]
  public int $id;

  #[EmailColumn]
  public string $email;
  
  #[Column(name: 'first_name', alias: 'firstName', dataType: SQLDataTypes::VARCHAR, dataTypeSize: 50)]
  public string $firstName;

  #[CreateDateColumn]
  public string $createdAt;

  #[UpdateDateColumn]
  public string $updatedAt;

  #[DeleteDateColumn]
  public string $deletedAt;
}

?>