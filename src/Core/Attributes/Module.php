<?php

namespace Assegai\Core\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Module
{
  public function __construct(
    public ?array $providers = [],
    public ?array $controllers = [],
    public ?array $imports = [],
    public ?array $exports = [],
  )
  {
  }
}

