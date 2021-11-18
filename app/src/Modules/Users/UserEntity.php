<?php

namespace Assegai\Modules\Users;

use Assegai\Database\Attributes\Columns\Column;
use Assegai\Database\Attributes\Columns\EmailColumn;
use Assegai\Database\Attributes\Columns\PasswordColumn;
use Assegai\Database\Attributes\Entity;
use Assegai\Database\BaseEntity;
use Assegai\Database\Queries\SQLDataTypes;

#[Entity(tableName: 'users', database: 'assegai_test')]
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