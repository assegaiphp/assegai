<?php

namespace LifeRaft\Database\Attributes\Columns;

use Attribute;
use LifeRaft\Database\Queries\SQLDataTypes;

/**
 * `DeleteDateColumn` is a special column that is automatically set to the entity's delete time each time you call soft-delete of entity manager or repository. You don't need to set this column - it will be automatically set. If the `#[DeleteDateColumn]` is set, the default scope will be "non-deleted".
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class DeleteDateColumn extends Column
{
  public function __construct(
    public string $name = 'deleted_at',
    public string $alias = 'deletedAt',
    public string $comment = '',
  )
  {
    parent::__construct(
      name: $name,
      alias: $alias,
      comment: $comment,
      dataType: SQLDataTypes::DATETIME,
      allowNull: false,
      defaultValue: '1000-01-01 00:00:00',
    );
  }
}

?>