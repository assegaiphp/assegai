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
  public function __construct(array|string $guards)
  {
    $actualGuards = [];

    if (is_array($guards))
    {
      foreach ($guards as $guard)
      {
        if (is_string($guard))
        {
          $actualGuards[] = $guard;
        }
      }
    }
    else
    {
      $actualGuards[] = $guards;
    }

    $this->guards = $actualGuards;
  }
}