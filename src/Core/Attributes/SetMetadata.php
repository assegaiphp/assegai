<?php

namespace Assegai\Core\Attributes;

use Attribute;

#[Attribute]
class SetMetadata
{
  public function __construct(
    public readonly string $key,
    public readonly mixed $value
  ) { }
}