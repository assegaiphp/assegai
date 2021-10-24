<?php

namespace LifeRaft\Database\Attributes\Columns;

use Attribute;
use LifeRaft\Database\Queries\SQLDataTypes;

/**
 * `CreateDateColumn` is a special column that is automatically set to the entity's insertion date. You don't need to set this column - it will be automatically set.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class CreateDateColumn extends Column
{
  public function __construct(
    public string $name = 'created_at',
    public string $alias = 'createdAt',
    public string $comment = '',
  )
  {
    parent::__construct(
      name: $name,
      alias: $alias,
      comment: $comment,
      dataType: SQLDataTypes::DATETIME,
      allowNull: false,
      defaultValue: 'CURRENT_TIMESTAMP'
    );
  }
}

?>