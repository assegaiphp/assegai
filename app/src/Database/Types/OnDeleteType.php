<?php

namespace LifeRaft\Database\Types;

/**
 * ON_DELETE type to be used to specify delete strategy when some relation is being deleted from the database.
 */
final class OnDeleteType
{
  
  public function __construct(
    private ?string $value = null
  ) { }

  public function __toString(): string
  {
    return $this->value;
  }

  public static function RESTRICT(): OnDeleteType {
    return new OnDeleteType(value: 'RESTRICT');
  }

  public static function CASCADE(): OnDeleteType {
    return new OnDeleteType(value: 'CASCADE');
  }

  public static function SET_NULL(): OnDeleteType {
    return new OnDeleteType(value: 'SET NULL');
  }

  public static function DEFAULT(): OnDeleteType {
    return new OnDeleteType(value: 'DEFAULT');
  }

  public static function NO_ACTION(): OnDeleteType {
    return new OnDeleteType(value: 'NO ACTION');
  }
}

?>