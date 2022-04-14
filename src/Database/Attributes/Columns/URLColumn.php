<?php

namespace Assegai\Database\Attributes\Columns;

use Assegai\Database\Queries\SQLDataTypes;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class URLColumn extends Column
{
  public function __construct(
    public string $name,
    public string $alias = '',
    public string $comment = '',
  )
  {
    parent::__construct(
      name: $name,
      alias: $alias,
      comment: $comment,
      dataType: SQLDataTypes::TEXT,
      allowNull: true,
      defaultValue: NULL
    );
  }
}