<?php

namespace Assegai\Core\Injection;

abstract class StaticProvider
{
  public function __construct(
    public readonly string $provide,
    public readonly ?bool $multi = null,
    public readonly mixed $useExisting = null
  ) { }
}