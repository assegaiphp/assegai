<?php

namespace LifeRaft\Database\Types;

final class OrphanedRowActionType
{
  public function __construct(
    private ?string $value = null
  ) { }

  public function __toString(): string
  {
    return $this->value;
  }

  public static function nullify(): OrphanedRowActionType
  {
    return new OrphanedRowActionType(value: 'nullify');
  }

  public static function delete(): OrphanedRowActionType
  {
    return new OrphanedRowActionType(value: 'delete');
  }
}

?>