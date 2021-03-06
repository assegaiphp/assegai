<?php

namespace Assegai\Database\Attributes\Columns;

use Attribute;
use Assegai\Database\Queries\SQLDataTypes;

#[Attribute(Attribute::TARGET_PROPERTY)]
class EmailColumn extends Column
{
  public function __construct(
    public string $name = 'email',
    public string $alias = '',
    public string $comment = '',
  )
  {
    parent::__construct(
      name: $name,
      alias: $alias,
      dataType: SQLDataTypes::VARCHAR,
      lengthOrValues: 60
    );
  }
}

?>