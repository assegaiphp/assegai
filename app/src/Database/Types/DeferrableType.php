<?php

namespace LifeRaft\Database\Types;

/**
 * DEFERRABLE type to be used to specify if foreign key constraints can be deferred.
 */
final class DeferrableType
{
  public function __construct(
    private ?string $value = null
  ) { }

  public function __toString(): string
  {
    return $this->value;
  }

  public static function INITIALLY_IMMEDIATE(): DeferrableType
  {
    return new DeferrableType(value: 'INITIALLY_IMMEDIATE');
  }

  public static function INITIALLY_DEFERRED(): DeferrableType
  {
    return new DeferrableType(value: 'INITIALLY_DEFERRED');
  }
}

?>