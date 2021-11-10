<?php

namespace Assegai\Core\Routing\Types;

final class PathMatchingStrategy
{
  public function __construct(
    private string $value
  ) { }

  public function __toString(): string
  {
    return $this->value;
  }

  public static function PREFIX(): PathMatchingStrategy
  {
    return new PathMatchingStrategy(value: 'prefix');
  }

  public static function FULL(): PathMatchingStrategy
  {
    return new PathMatchingStrategy(value: 'full');
  }
}

?>