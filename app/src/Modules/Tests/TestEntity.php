<?php

namespace LifeRaft\Modules\Test;

use LifeRaft\Database\Attributes\Columns\Column;
use LifeRaft\Database\Attributes\Columns\CreateDateColumn;
use LifeRaft\Database\Attributes\Columns\DeleteDateColumn;
use LifeRaft\Database\Attributes\Columns\PrimaryGeneratedColumn;
use LifeRaft\Database\Attributes\Columns\UpdateDateColumn;
use LifeRaft\Database\BaseEntity;

class TestEntity extends BaseEntity
{
  #[PrimaryGeneratedColumn]
  public int $id;

  #[Column(isUnique: true)]
  public string $email;
  
  #[Column(name: 'first_name', alias: 'firstName')]
  public string $firstName;

  #[CreateDateColumn]
  public string $createdAt;

  #[UpdateDateColumn]
  public string $updatedAt;

  #[DeleteDateColumn]
  public string $deletedAt;
}

?>