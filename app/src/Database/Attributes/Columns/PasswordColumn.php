<?php

namespace LifeRaft\Database\Attributes\Columns;

use Attribute;
use LifeRaft\Core\Config;
use LifeRaft\Database\Queries\SQLDataTypes;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class PasswordColumn extends Column
{
  private mixed $passwordHashAlgorithm = null;
  public function __construct(
    public string $name = 'password',
    public string $alias = '',
    public string $comment = '',
  )
  {
    $this->passwordHashAlgorithm = Config::get('default_password_hash_algo');

    if (empty($this->passwordHashAlgorithm))
    {
      $this->passwordHashAlgorithm = PASSWORD_DEFAULT;
    }

    parent::__construct(
      name: $name,
      alias: $alias,
      comment: $comment,
      dataType: SQLDataTypes::VARCHAR,
      lengthOrValues: 100,
      defaultValue: password_hash('liferaft', $this->passwordHashAlgorithm)
    );
  }
}

?>