<?php

namespace Assegai\Database\Attributes\Relations;

final class JoinColumnOptions
{
  /**
   * @param null|string $name Name of the column.
   * @param null|string $referencedColumn Name of the column in the entity to which this column is referenced.
   */
  public function __construct(
    public ?string $name = null,
    public ?string $referencedColumn = null,
  ) { }
}