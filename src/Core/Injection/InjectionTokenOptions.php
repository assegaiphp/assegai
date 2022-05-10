<?php

namespace Assegai\Core\Injection;

class InjectionTokenOptions
{
  public function __construct(
    public readonly string $type,
    public readonly ?string $providedIn = 'root',
    public readonly ?callable $factory = null
  ) { }
}