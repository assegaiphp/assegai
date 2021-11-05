<?php

namespace LifeRaft\Modules\Users;

use LifeRaft\Database\Attributes\Columns\Column;
use LifeRaft\Database\Attributes\Columns\EmailColumn;
use LifeRaft\Database\Attributes\Columns\PasswordColumn;
use LifeRaft\Database\Attributes\Entity;
use LifeRaft\Database\BaseEntity;
use LifeRaft\Database\Queries\SQLDataTypes;

#[Entity(tableName: 'users')]
class UserEntity extends BaseEntity
{
  #[Column(dataType: SQLDataTypes::VARCHAR, lengthOrValues: 60, isUnique: true)]
  public string $username = '';

  #[EmailColumn]
  public string $email = '';

  #[PasswordColumn]
  public string $password = '';

  #[Column(name: 'password_updated_at', dataType: SQLDataTypes::DATETIME, defaultValue: 'CURRENT_TIMESTAMP', canUpdate: false)]
  public string $passwordUpdatedAt = '';

  #[Column(name: 'first_name', dataType: SQLDataTypes::VARCHAR, lengthOrValues: 50)]
  public string $firstName = '';

  #[Column(name: 'last_name', dataType: SQLDataTypes::VARCHAR, lengthOrValues: 50)]
  public string $lastName = '';

  #[Column(name: 'is_verified', dataType: SQLDataTypes::BOOLEAN, defaultValue: false)]
  public bool $isVerified = false;
}

?>