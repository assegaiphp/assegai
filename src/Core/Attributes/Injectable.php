<?php

namespace Assegai\Core\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Injectable
{
  public function __construct()
  {
  }
}

