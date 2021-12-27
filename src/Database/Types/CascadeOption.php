<?php

namespace Assegai\Database\Types;

final class CascadeOption
{
  public function __construct(
    private ?string $value = null
  ) { }

  public function __toString(): string
  {
    return $this->value;
  }

  public static function INSERT() {
    return new CascadeOption(value: 'insert');
  } 

  public static function UPDATE() {
    return new CascadeOption(value: 'update');
  } 

  public static function REMOVE() {
    return new CascadeOption(value: 'remove');
  }

  public static function SOFT_REMOVE() {
    return new CascadeOption(value: 'soft-remove');
  }

  public static function RECOVER() {
    return new CascadeOption(value: 'recover');
  } 
}

?>