<?php

namespace Assegai\Core\Attributes;

use Assegai\Core\Interfaces\ICanActivate;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class UseGuards
{
  public readonly array $guards;
  /**
   * @param array<int, ICanActivate> $guards The list of Guards to use. 
   */
  public function __construct(array $guards)
  {
    $actualGuards = [];

    foreach ($guards as $guard)
    {
      if ($guard instanceof ICanActivate)
      {
        $actualGuards[] = $guard;
      }
    }

    $this->guards = $actualGuards;
  }
}