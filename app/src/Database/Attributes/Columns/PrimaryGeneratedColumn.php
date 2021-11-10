<?php

namespace Assegai\Database\Attributes\Columns;

use Attribute;
use Assegai\Database\Queries\SQLDataTypes;

#[Attribute(Attribute::TARGET_PROPERTY)]
class PrimaryGeneratedColumn extends Column
{
  public function __construct(
    public string $name = 'id',
    public string $alias = '',
    public string $comment = ''
  )
  {
    parent::__construct(
      name: $name,
      alias: $alias,
      comment: $comment,
      dataType: SQLDataTypes::BIGINT_UNSIGNED,
      allowNull: false,
      signed: false,
      zeroFilled: false,
      autoIncrement: true,
      isPrimaryKey: true
    );
  }
}

?>