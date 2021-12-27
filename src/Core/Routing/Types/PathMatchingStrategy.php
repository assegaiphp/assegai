<?php

namespace Assegai\Core\Routing\Types;

use JetBrains\PhpStorm\Pure;

final class PathMatchingStrategy
{
  public function __construct(
    private string $value
  ) { }

  public function __toString(): string
  {
    return $this->value;
  }

  #[Pure]
  public static function PREFIX(): PathMatchingStrategy
  {
    return new PathMatchingStrategy(value: 'prefix');
  }

  #[Pure]
  public static function FULL(): PathMatchingStrategy
  {
    return new PathMatchingStrategy(value: 'full');
  }
}

